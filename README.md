# Symfony Image Crop Bundle

Бандл для Symfony для обрезания изображения по координатам.
 
### Зависимости 

* Symfony 4.*
* Imagick 3.4.*

### Установка

1. Добавить этот бандл в проект как зависимость composer.
```
composer require goyden/image-crop-bundle
```
2. Добавить этот бандл в bundles.php вашего проекта.
```php
// config/bundles.php
return [
    // ...
    App\Goyden\ImageCropBundle\GoydenImageCropBundle::class => []
]
```

## Использование

* В случае ошибок выбрасываются исключения.
* Методы могут быть вызваны в любом порядке.
* Обязательны только методы crop и setFile.
* setFile должен быть вызван до crop.
* setFile принимает объект класса Symfony\Component\HttpFoundation\File\File.
* setThumbnail подгужает размеры для обрезания из файла конфигурации. Подробнее в разделе "Конфигурация".
* Аргументы x1 и y1 в методе setCoordinates опциональны если позднее вызывается setThumbnail.
* Все координаты по умолчанию равны 0.

```php
$this->container->get('goyden_image_crop')

// Задаем файл, который будем резать.
->setFile(File $file)

// Задаем координаты, по которым будет сделано обрезание (x1 и y1 опциональны).
->setCoordinates(int $x, int $y, int $x1, int $y1)

// Выбираем миниатюру из файла конфигурации.
->setThumbnail(string $thumbnail)

// Обрезаем файл.
->crop()

// Получаем файл в виде строки.
->getFileString()
```

## Конфигурация

* Файл включает в себя название и размеры миниатюр.
* Миниатюр может быть любое количество.
* Оба параметра в миниатюре обязательны. Они должны быть не меньше 0. 

```yaml
# config/package/goyden_image_crop.yaml
goyden_image_crop:
  thumbnail:
    small:
      width: 40
      height: 40
    big:
      width: 60
      height: 60
```