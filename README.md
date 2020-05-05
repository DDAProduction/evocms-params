# Product Params


## Install

1) Install extras templatesEdit3
2) `php artisan package:installrequire ddaproduction/evocms-params "*"` in you **core/** folder
3) `php artisan migrate --path=vendor/ddaproduction/evocms-params/database/migrations` 

## Уточнения
Пример кастомизации лежит в папке example в трёх файлах:
dda_params_global_product_params - для глобальных параметров
dda_params_custom_tabs - для дополнительных табов 
dda_params_tabs_sort - для сортировки табов и переименовывания стандартных
dda_params_products_template - кастомный конфиг для Template Edit, который будет использоваться вместо зашитого в код. Важно чтобы оставлись с 12 по 43 строку + в один из табов помещалась переменная `$paramsTemplateEditor`


