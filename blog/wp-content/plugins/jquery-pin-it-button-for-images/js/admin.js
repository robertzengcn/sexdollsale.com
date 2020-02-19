(function ($) {

    var modelModule = function () {

        var _classes = [];

        function addClasses(classes) {
            var classNames = classes.indexOf(';') >= 0 ? classes.split(';') : [classes];

            for (var i = 0; i < classNames.length; i++) {
                if (_classes.indexOf(classNames[i]) < 0 && classNames[i].length > 0)
                    _classes.push(classNames[i]);
            }
        }

        function deleteClass(className) {
            var index = _classes.indexOf(className);
            if (index >= 0)
                _classes.splice(index, 1);
        }

        function getFormatted() { return _classes.join(';'); }

        function init(formatted) {
            formatted = formatted || '';
            var toAdd = formatted.split(';')

            for (var i = 0; i < toAdd.length; i++) {
                if (toAdd[i].length != 0) {
                    _classes.push(toAdd[i]);
                }
            }
        }

        return {
            addClasses: addClasses,
            deleteClass: deleteClass,
            getClasses: function(){ return _classes; },
            getFormatted: getFormatted,
            init: init
        };
    };
    
    var guiModule = function(){
        
        var _model = modelModule();
        var _valueInput,
            _classNameInput,
            _classButton,
            _noClassesSpan,
            _classesUl;
        
        function updateNoClassesSpan(){
            var classes = _model.getClasses();
            _noClassesSpan.toggle(classes.length == 0);
        }
            
        function updateUl(){
            var classes = _model.getClasses();
            var html = '';
            for(var i = 0; i < classes.length; i++){
                html += '<span><a class="jpibfi-delete">X</a>' + classes[i] + '</span>';
            }
            _classesUl.html(html);
        }
        
        function updateValueInput(){
            _valueInput.val(_model.getFormatted());
        }
        
        function init($container){
            _valueInput = $container.find('.jpibfi-value');
            _classNameInput = $container.find('.jpibfi-class-name');
            _classButton = $container.find('.jpibfi-class-button');
            _noClassesSpan = $container.find('.jpibfi-empty');
            _classesUl = $container.find('.jpibfi-classes-list');
            
            _model.init(_valueInput.val());
            
            _classButton.click(function(){
                var className = _classNameInput.val();
                _model.addClasses(className);
                _classNameInput.val('');
                updateUI();
            });
            
            _classesUl.delegate('.jpibfi-delete', 'click', function(){
                var className = $(this).parent().text().substr(1);
                _model.deleteClass(className);
                updateUI();
            });
            
            updateUI();
        }
        
        function updateUI(){
            updateValueInput();
            updateUl();
            updateNoClassesSpan();
        }
        
        return {
            init: init,
        };
    };
    
    var disabledClassesModule = guiModule(),
        enabledClassesModule = guiModule();

    var settings = (function(){
        var settings = {
            version: 'lite'
        };

        if (window.jpibfiAdminSettings){
            $.extend(settings, window.jpibfiAdminSettings);
        }
        return settings;
    })();
    
    $(document).ready(function ($) {

        disabledClassesModule.init($('#jpibfi-disabled-classes'));
        enabledClassesModule.init($('#jpibfi-enabled-classes'));

        $('a.jpibfi_selector_option').click(function (e) {
            $('#image_selector').val($(this).text());
            e.preventDefault();
        });

        $("#refresh_custom_button_preview").click(function (e) {
            var customWidth = $('#custom_image_width').val();
            var customHeight = $('#custom_image_height').val();
            var customUrl = $('#custom_image_url').val();

            $('#custom_button_preview').css({
                width: customWidth,
                height: customHeight,
                "background-image": "url('" + customUrl + "')"
            });
            return false;
        });

        $('#jpibfi_remove_ad').click(function(){
            $('.jpibfi-pro-notice').remove();
            jQuery.post(ajaxurl, { 'action': 'jpibfi_remove_pro_ad' }, function(response) {});
        });

        if ($('#show_button_error').length > 0){
            $('#show_button').change(function(e){
                $('#show_button_error').toggle($(this).val() != 'hover');
            });
        }
    });
})(jQuery);