$(document).ready(function()
{
  $('body').addClass('isenselabs-module isl-' + islConfig.name);
  $(document).trigger('isl.init');

  // Main navigation
  $('#main-tabs a:first').tab('show'); // Select first tab
  if (window.localStorage && window.localStorage['currentTab-' + islConfig.name]) {
    $('#main-tabs a[href="' + window.localStorage['currentTab-' + islConfig.name] + '"]').tab('show');
  }
  $('#main-tabs a[data-toggle="tab"]').click(function() {
    if (window.localStorage) {
      window.localStorage['currentTab-' + islConfig.name] = $(this).attr('href');
    }
  });

  $('.section-tabs, .lang-tabs').each(function() {
    $(this).find('a:first').tab('show');
  });

  if (islConfig.support) {
    $('a[href=#isense_support]').trigger('click');
  }

  // Date chooser
  $('.date').datetimepicker({
    pickTime: false
  });
  $('.date').on('focus', 'input', function(e) {
    $(this).parent().find('button').trigger('click');
  });

});

$(document).on('isl.init isl.summernote', function(e)
{
  $('[data-isl-summernote]').each(function() {
    applySummernote(this);
  });
});

$(document).on('isl.init isl.toggle', function(e)
{
  /**
   * usage:
   * - data-isl-toggle='{"target":"identifier_class_suffix"}'
   * - Hide all identifier_class_suffix, show identifier_class_suffix_v_inputValue
   */
  $('[data-isl-toggle]').each(function() {
    var el   = this,
      param  = $(el).data('isl-toggle'),
      target = '';

    $(el).on('change', {param: param},function(e) {
      target = e.data.param.target + '_v_' + $(this).val();

      $('.' + e.data.param.target).hide();
      if ($('.' + e.data.param.target).hasClass(target)) {
        $('.' + target).slideDown('fast');
      }
    });
  });
});

$(document).on('isl.init isl.autocomplete', function(e)
{
  /**
   * usage:
   * - data-isl-autocomplete='{"type":"product", "target":"unique_well_class_suffix", "name":"input_name"}'
   * - data-isl-autocomplete='{"type":"post", "target":"unique_well_class_suffix", "name":"input_name", "route":"extension/module/iblogs"}'
   */
  $('[data-isl-autocomplete]').each(function() {
    var el  = this,
      param = $(el).data('isl-autocomplete'),
      route = param.type !== 'customer' ? 'catalog/' + param.type : 'customer/customer';

      if (typeof param.route !== 'undefined') {
        route = param.route;
      }

    $(el).autocomplete({
      source: function(request, response) {
        $.ajax({
          url: 'index.php?route=' + route + '/autocomplete&' + islConfig.url_token + '&filter_type=' + param.type + '&filter_name=' +  encodeURIComponent(request),
          dataType: 'json',
          success: function(json) {
            response($.map(json, function(item) {
              return {
                label: item.name,
                value: item[param.type + '_id']
              };
            }));
          }
        });
      },
      select: function(item) {
        $(el).val('');
        $('.' + param.target + item.value).remove();
        $('.' + param.target).append('<div class="' + param.target + item.value + '"><i class="fa fa-minus-circle pointer"></i> ' + item.label + '<input type="hidden" name="' + param.name + '" value="' + item.value + '" /></div>');
      }
    });

    $('.' + param.target).on('click', '.fa-minus-circle', function() {
      $(this).parent().remove();
    });
  });
});

function applySummernote(el) {
  var param = $(el).data('isl-summernote');

  $(el).summernote({
    disableDragAndDrop: true,
    height: param !== undefined ? param : 200,
    emptyPara: '',
    toolbar: [
      ['style', ['style']],
      ['font', ['bold', 'underline', 'italic', 'clear']],
      // ['fontname', ['fontname']],
      // ['color', ['color']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['insert', ['table', 'link', 'image', 'video']],
      ['view', ['fullscreen', 'codeview', 'help']]
    ],
    buttons: {
      image: function() {
        var ui = $.summernote.ui;

        // create button
        var button = ui.button({
          contents: '<i class="note-icon-picture" />',
          tooltip: $.summernote.lang[$.summernote.options.lang].image.image,
          click: function () {
            $('#modal-image').remove();

            $.ajax({
              url: 'index.php?route=common/filemanager&' + islConfig.url_token,
              dataType: 'html',
              beforeSend: function() {
                $('#button-image i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
                $('#button-image').prop('disabled', true);
              },
              complete: function() {
                $('#button-image i').replaceWith('<i class="fa fa-upload"></i>');
                $('#button-image').prop('disabled', false);
              },
              success: function(html) {
                $('body').append('<div id="modal-image" class="modal">' + html + '</div>');

                $('#modal-image').modal('show');

                $('#modal-image').delegate('a.thumbnail', 'click', function(e) {
                  e.preventDefault();

                  $(el).summernote('insertImage', $(this).attr('href'));

                  $('#modal-image').modal('hide');
                });
              }
            });
          }
        });

        return button.render();
      }
    }
  });
}


/**
 * Set localStrage
 */
function editStorage(key, value) {
  localStorage.setItem(key+'-'+token_hash, JSON.stringify(value));
}

/**
 * Get localStrage
 */
function getStorage(key) {
  var param = '',
    key = key+'-'+token_hash;

  if (window.localStorage && window.localStorage[key]) {
    var savedData = JSON.parse(localStorage.getItem(key));
    if (savedData.length) {
      param = savedData;
    }
  }

  return param;
}
