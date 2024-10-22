(function ($) {
    tinymce.create('tinymce.plugins.iti_but_image_tinymce', {
        init: function (ed) {
            ed.addButton('iti_but_image_tinymce', {
                title: 'Загрузить изображение',
                icon: 'image',
                onclick: function () {
                    let input = document.createElement('input');
                    input.type = 'file';
                    input.accept = 'image/*';
                    input.onchange = function () {
                        let file = input.files[0];
                        let reader = new FileReader();
                        reader.onload = function (event) {
                            ed.execCommand('mceInsertContent', false, '<img src="' + event.target.result + '" />');
                        };
                        reader.readAsDataURL(file);
                    };
                    input.click();
                }
            });
        }
    });
    tinymce.PluginManager.add('iti_but_image_tinymce', tinymce.plugins.iti_but_image_tinymce);
})(jQuery);
