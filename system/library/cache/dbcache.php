<?php
/*------------------------------------------------------------------------
# Database Cache
# ------------------------------------------------------------------------
# The Krotek
# Copyright (C) 2011-2019 The Krotek. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: https://thekrotek.com
# Support: support@thekrotek.com
-------------------------------------------------------------------------*/

class DbCache
{
    private static $instance;
	private $cacheChanged = false;	
	private $cacheMap = array();
    
    protected function __construct()
    {
        $this->loadCacheFromFile();
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }
    	
    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        
        return static::$instance;
    }

    public static function isCreated()
    {
        return static::$instance !== null;
    }

    public function isChanged()
    {
        return $this->cacheChanged;
    }
		
    public function clear()
    {
        $dbCache = $this;
        $dirPath = $dbCache->getCacheDirPath();
        $files = scandir($dirPath);
        
        foreach ($files as $filePath) {
            if (($filePath == '.') || ($filePath == '..')) continue;
            
            unlink($dirPath.$filePath);
        }
        
        rmdir($dirPath);
    }

    public function getCacheDirPath()
    {
        $dirPath = DIR_CACHE.'dbcache/';
        
        if (!file_exists($dirPath)) mkdir($dirPath);
        
        return $dirPath;
    }

    public function loadCacheFromFile()
    {
        $cacheFilePath = $this->getCacheMapFilePath();
        
        if (!file_exists($cacheFilePath)) return;
        
        $handle = fopen($cacheFilePath, "r");
        
        flock($handle, LOCK_SH);
        
        $cacheSerialized = fread($handle, filesize($cacheFilePath));
        
        fclose($handle);
        
        $this->cacheMap = unserialize($cacheSerialized);
    }

    public function saveCacheToFile()
    {
        $cacheFilePath = $this->getCacheMapFilePath();    
        $cacheSerialized = serialize($this->cacheMap);
        
        $handle = fopen($cacheFilePath, 'w');
        
        flock($handle, LOCK_EX);
        fwrite($handle, $cacheSerialized);
        fflush($handle);
        fclose($handle);
    }

	public function addSelectFetchToCache($queryText, $fetchData)
	{
        $this->setCacheEntry($queryText, $fetchData);
	}
	
	public function getCachedSelectFetch($queryText)
	{
        $cachedFetchData = $this->getCachedDataFromCacheMap($queryText);
        
        return $cachedFetchData;
	}
	
	public function processModificationQuery($queryText)
	{
		$dbTableNamesInQuery = DbCache::extractDbTableNamesFromQueryText($queryText);
		
		foreach ($this->cacheMap as $queryTextKey => $cacheEntry) {
            foreach ($dbTableNamesInQuery as $dbTableName) { 
                if (stripos($queryTextKey, $dbTableName)) {
                    $this->removeCacheEntry($queryTextKey);
                }   
            }
		}
	}
	
	public static function isModificationQuery($queryText)
	{
        $arReadQueries = array('select', 'show tables', 'show columns');
        
        foreach ($arReadQueries as $queryRead) {
            $striposSelect = stripos(trim($queryText), $queryRead);
            
            if ($striposSelect === 0 || $striposSelect === '0') return false;
        }
        
        return true;
	}
	
	public static function extractDbTableNamesFromQueryText($queryText)
	{
		$tableNames = preg_grep('/'.DB_PREFIX.'.+/', explode(' ', $queryText));
		
        return $tableNames;
	}
	
	public static function processDbQuery($db, $sql, $params = null)
	{
		if ($params === null) $params = array();
		
		$dbCache = DbCache::getInstance();
		
        if (stripos($sql, 'now()')) {
            $c = 0;
            $sql = str_ireplace('NOW()', '\''.date('Y-m-d H:i').':00\'', $sql, $c);
        }
        
        if (DbCache::isModificationQuery($sql)) {
            $dbCache->processModificationQuery($sql);
        } else {
			if (!Registry::getInstance()->get('config')->get((version_compare(VERSION, '3', '>=') ? 'module_' : '').'dbcache_status')) {
                return $db->defaultQuery($sql);
        	}
        	
            if (stripos($_SERVER['REQUEST_URI'], '/admin') !== false) {
                $cachedFetch = $dbCache->getCachedSelectFetch($sql);
                
                if ($cachedFetch != null) {
                    return $cachedFetch;
                }
            } else {
                return $db->defaultQuery($sql);
            }
        }

		$freshDbFetch = $db->defaultQuery($sql);
		
        if (!DbCache::isModificationQuery($sql)) {
            $dbCache->addSelectFetchToCache($sql, $freshDbFetch);   
        }
        
        return $freshDbFetch;
	}

    private static function getCacheTimeout()
    {
        $timeout = (int)Registry::getInstance()->get('config')->get((version_compare(VERSION, '3', '>=') ? 'module_' : '').'dbcache_timeout');
        
        if (!$timeout) $timeout = 3600;
        
        return $timeout;
    }
    
    private function getCacheMapFilePath()
    {    
        $dir = DIR_DOWNLOAD.'dbcache/';
        $dbCacheFilePath = $dir.'dbcache.dat';
        
        if (file_exists($dbCacheFilePath)) unlink($dbCacheFilePath);
        
        if (file_exists($dir)) rmdir($dir);

        $latestPath = $this->getCacheDirPath();
        $latestPath .= '_cache';

        return $latestPath;
    }

    private function getCachedDataFromCacheMap($queryText)
    {    
		if (isset($this->cacheMap[$queryText]) && ($this->cacheMap[$queryText] != null)) {
            $cachedEntry = $this->cacheMap[$queryText];
			$cacheTime = $cachedEntry['time'];
			$nowTime = date_create();
			$secondsDiffSpan = date_diff($cacheTime, $nowTime);
            $daysDiffCount = $secondsDiffSpan->format('%a');
            
			if ($daysDiffCount >= self::getCacheTimeout()) {
                $this->removeCacheEntry($queryText);
                
				return null;
			}
			
			return $cachedEntry['data'];
		}
		
		return null;
    }

    private function getCacheEntryFilePath($cacheKey)
    {
        return $this->getCacheDirPath().$this->getCacheEntryFileNameByHash($this->getSelectQueryHash($cacheKey));
    }

    private function getCacheEntryFileNameByHash($hash)
    {
        return $hash;
    }

    private function getCacheEntryData($cacheKey)
    {
        if (!file_exists($this->getCacheEntryFilePath($cacheKey))) return null;

        $nowTime = date_create();
        
        $timeModified = date_create();

        date_timestamp_set($timeModified, filemtime($this->getCacheEntryFilePath($cacheKey)));
        
		$secondsDiffSpan = date_diff($timeModified, $nowTime);
        $daysDiffCount = $secondsDiffSpan->format('%a');
        
		if ($daysDiffCount >= self::getCacheTimeout()) {
            $this->removeCacheEntry($cacheKey);
            
			return null;
		}
		
		return unserialize(file_get_contents($this->getCacheEntryFilePath($cacheKey)));
    }

    private function removeCacheEntry($queryTextKey)
    {
        if (isset($this->cacheMap[$queryTextKey])) {
            $this->cacheMap[$queryTextKey] = null;
            
            unset($this->cacheMap[$queryTextKey]);   
            
            $this->cacheChanged = true;
        }
    }

    private function getSelectQueryHash($queryText)
    {
        return md5($queryText);
    }

    private function setCacheEntry($cacheKey, $cacheData)
    {
		$cachedTime = date_create();
		$this->cacheMap[$cacheKey] = array('time' => $cachedTime, 'data' => $cacheData);
        $this->cacheChanged = true;
    }	
}

?>