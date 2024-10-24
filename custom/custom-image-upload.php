<?php

add_action('it_cabinet_get_user', function ($user) {
    $image = get_user_meta($user->id, 'image', true);
    $image_head = get_user_meta($user->id, 'image_head', true);

    $user->image = $image ?: create_image($user->firstname);
    $user->image_head = $image_head ?: WP_ITI_CABINET_URL . 'images/default-head.jpg';
});
function iti_upload_file_execute($name_field)
{

    if (isset($_FILES[$name_field]) && $_FILES[$name_field]['error'] === UPLOAD_ERR_OK && $_FILES[$name_field]['size'] !== 0) {
        // Файл был загружен успешно
        error_log(print_r($_FILES[$name_field], true));

        $fileTmpPath = $_FILES[$name_field]['tmp_name'];
        $fileName = $_FILES[$name_field]['name'];
        $fileSize = $_FILES[$name_field]['size'];
        $fileType = $_FILES[$name_field]['type'];

        $upload_dir = wp_upload_dir();

        // Путь для сохранения файла (например, в папку uploads)
        $uploadFileDir = $upload_dir['basedir'] . '/profiles/';
        if (!file_exists($uploadFileDir)) {
            wp_mkdir_p($uploadFileDir);
        }
        $fileName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $fileName);
        $dest_path = $uploadFileDir . $fileName;

        // Перемещаем файл в директорию
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            return $upload_dir['baseurl'] . '/profiles/' . $fileName;
        } else {
            return false;
        }
    }
}

add_action('iti_cabinet_update_user_profile', function ($user_id) {
    $file = iti_upload_file_execute('user_image');
    if ($file) {
        update_user_meta($user_id, 'image', $file);
    }

    $file = iti_upload_file_execute('user_image_head');
    if ($file) {
        update_user_meta($user_id, 'image_head', $file);
    }
});

add_action('it_cabinet_fields_profile_edit_bottom', function ($user) {
    ?>
    <div class="form-group">
        <label for="user_image" class="control-label"></label>
        <div class="control-editor">
            <input type="file" id="user_image" name="user_image" accept="image/*" style="display:none;">
            <img id="image_preview" style="display:block; max-width: 150px; max-height: 150px;" alt=""
                 src="<?php echo $user->image; ?>" class="image-preview">
            <button type="button" class="control-but control-but_default" id="user_image_button">Поменять</button>
        </div>
    </div>
    <div class="form-group">
        <label for="user_image_head" class="control-label"></label>
        <div class="control-editor">
            <input type="file" id="user_image_head" name="user_image_head" accept="image/*" style="display:none;">
            <img id="image_head_preview" style="display:block; max-width: 300px; max-height: 150px;" alt=""
                 src="<?php echo $user->image_head; ?>" class="image-preview">
            <button type="button" class="control-but control-but_default" id="user_image_head_button">Поменять</button>
        </div>
    </div>
    <?php
});

add_action('wp_footer', function () {
    ?>
    <style>
        #cropModal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            z-index: 1000;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            max-width: 460px;
        }

        #cropModal .modal-body {
            margin-bottom: 10px;
        }

        #cropModal .modal-body img {
            width: 100%;
            max-width: none;
            height: auto;
        }

        /*.jcrop-holder {*/
        /*    background-color: transparent !important;*/
        /*}*/
    </style>
    <div id="cropModal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Обрезка изображения</h5>
            </div>
            <div class="modal-body">
                <img id="cropImage" src="" alt="Изображение для обрезки">
            </div>
            <div class="modal-footer">
                <button id="cropModal_saveButton" class="control-but control-but_primary">Сохранить</button>
                <button id="cropModal_closeButton" class="control-but control-but_default">Закрыть</button>
            </div>
        </div>
    </div>
    <script>
        (function ($) {
            let jcropApi;
            let coordsCrop;
            let croppedFile;

            let $modal = $('#cropModal');
            let $cropImage = $('#cropImage');
            let $modalButClose = $('#cropModal_closeButton');
            let $modalButSave = $('#cropModal_saveButton');

            let $imageFile = $('#user_image');
            let $imageView = $('#image_preview');
            let $imageViewBut = $('#user_image_button');

            let $imageHeadFile = $('#user_image_head');
            let $imageHeadView = $('#image_head_preview');
            let $imageHeadViewBut = $('#user_image_head_button');

            let $targetFileImage = $imageFile;
            let $targetViewImage = $imageView;

            let  JcropOptions = {
                bgColor: '#eeeeee', // Цвет фона
                bgOpacity: 0.3, // Прозрачность фона
            };

            $modalButClose.on('click', function (e) {
                $modal.hide();

                $cropImage.css('width', '');
                $cropImage.css('height', '');
            });

            $modalButSave.on('click', function (e) {
                cropImage();

                $modalButClose.click();
            });

            $imageViewBut.on('click', function (e) {
                $imageFile.click();
            });

            $imageHeadViewBut.on('click', function (e) {
                $imageHeadFile.click();
            });

            $imageHeadFile.on('change', function (event) {
                let minWidth = 1150;
                let minHeight = 150;

                let inputFile = this.files[0];

                if (inputFile) {
                    $targetViewImage = $imageHeadView;
                    $targetFileImage = $imageHeadFile;

                    let reader = new FileReader();

                    reader.onload = function (e) {
                        $cropImage.attr('src', e.target.result);

                        $cropImage.on('load', function () {

                            $modal.show();

                            // Уничтожаем предыдущий экземпляр Jcrop, если существует
                            if (jcropApi) {
                                jcropApi.destroy();
                            }

                            // Инициализация Jcrop
                            $cropImage.Jcrop({
                                aspectRatio: minWidth / minHeight, // Соотношение сторон (например, 1:1)
                                onSelect: updateCoords, // Функция для обработки координат
                                setSelect: [0, 0, 20000, 20000],
                                bgColor: JcropOptions.bgColor, // Цвет фона
                                bgOpacity:  JcropOptions.bgOpacity, // Прозрачность фона
                            }, function () {
                                jcropApi = this; // Сохраняем экземпляр Jcrop
                            });
                        })
                    };

                    reader.readAsDataURL(inputFile);
                }
            });

            $imageFile.on('change', function (event) {

                let inputFile = this.files[0];

                if (inputFile) {
                    $targetViewImage = $imageView;
                    $targetFileImage = $imageFile;

                    let reader = new FileReader();

                    reader.onload = function (e) {
                        $cropImage.attr('src', e.target.result);

                        $cropImage.on('load', function () {

                            $modal.show();

                            // Уничтожаем предыдущий экземпляр Jcrop, если существует
                            if (jcropApi) {
                                jcropApi.destroy();
                            }

                            // Инициализация Jcrop
                            $cropImage.Jcrop({
                                aspectRatio: 1, // Соотношение сторон (например, 1:1)
                                onSelect: updateCoords, // Функция для обработки координат
                                setSelect: [0, 0, 20000, 20000],
                                bgColor: JcropOptions.bgColor, // Цвет фона
                                bgOpacity:  JcropOptions.bgOpacity, // Прозрачность фона
                            }, function () {
                                jcropApi = this; // Сохраняем экземпляр Jcrop
                            });
                        })
                    };

                    reader.readAsDataURL(inputFile);
                }
            });

            // Функция для обрезки изображения
            function cropImage() {
                if (jcropApi) {
                    let $cropImg = document.querySelector('#cropImage + .jcrop-holder img'); // Ссылка на элемент изображения

                    // Проверка размеров изображения
                    const naturalWidth = $cropImg.naturalWidth;  // Оригинальная ширина изображения
                    const naturalHeight = $cropImg.naturalHeight; // Оригинальная высота изображения

                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');

                    // Приведение координат к натуральным размерам изображения
                    const scaleX = naturalWidth / $cropImg.width;  // Соотношение ширины
                    const scaleY = naturalHeight / $cropImg.height; // Соотношение высоты

                    // Устанавливаем размеры canvas по оригинальным размерам области обрезки
                    canvas.width = coordsCrop.w * scaleX;  // Натуральная ширина обрезки
                    canvas.height = coordsCrop.h * scaleY; // Натуральная высота обрезки

                    // Рисуем обрезанную область на canvas с учетом натуральных размеров
                    context.drawImage(
                        document.querySelector('#cropImage'),
                        coordsCrop.x * scaleX, coordsCrop.y * scaleY, coordsCrop.w * scaleX, coordsCrop.h * scaleY, // Масштабирование координат
                        0, 0, canvas.width, canvas.height // Область на canvas
                    );

                    // Получаем обрезанное изображение как Blob
                    canvas.toBlob(function (blob) {
                        // Создаем новый файл на основе Blob
                        croppedFile = new File([blob], 'image' + Date.now() + '.png', {type: 'image/png'});

                        // Заменяем файл в input type="file"
                        const fileInput = $targetFileImage.get(0);
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(croppedFile);
                        fileInput.files = dataTransfer.files;

                        // Теперь можно отправить обрезанное изображение на сервер
                        console.log('Обрезанное изображение готово для загрузки на сервер:', fileInput.files[0]);

                        // Вставляем обрезанное изображение на страницу
                        const croppedImageDataUrl = URL.createObjectURL(blob);
                        const croppedImage = $targetViewImage.get(0);
                        croppedImage.src = croppedImageDataUrl;
                        croppedImage.style.display = 'block';
                        croppedImage.style.width = 'auto';
                        croppedImage.style.height = 'auto';
                        croppedImage.style.visibility = 'visible';

                        // Удаляем Jcrop после обрезки
                        jcropApi.destroy();
                        console.log('Jcrop инструмент удален');

                    }, 'image/png');

                }
            }

            // Функция для обновления координат (если нужно)
            function updateCoords(c) {
                coordsCrop = c;
                console.log(coordsCrop);
            }

        })(jQuery);
    </script>

    <!-- Подключение Jcrop CSS -->
<!--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-jcrop/0.9.15/css/jquery.Jcrop.min.css"/>-->
    <link rel="stylesheet" href="<?php echo WP_ITI_CABINET_URL; ?>/assets/jcrop/jquery.Jcrop.min.css"/>

    <!-- Подключение Jcrop JS -->
<!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-jcrop/0.9.15/js/jquery.Jcrop.js"></script>-->
    <script src="<?php echo WP_ITI_CABINET_URL; ?>/assets/jcrop/jquery.Jcrop.js"></script>
    <?php
//    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet"/>
//    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
});
