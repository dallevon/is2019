# Империя Шапок

## Инструкции для импортирования данных из 1С

### Таблица: **\***\_virtuemart_categories

При импортировании категорий продуктов из 1С в таблицу **\***\_virtuemart_categories, выставлять значения полей:

- `category_template`=NULL
- `category_layout`='default'
- `category_product_layout`='default'
- `products_per_row`=0
- `limit_list_step`=NULL
- `limit_list_initial`=NULL
- `has_medias`=1

### Таблица **\***\_virtuemart_category_medias
![image](https://user-images.githubusercontent.com/14219112/199749952-2a9d7ae9-fc44-484a-be01-a36cdcb04810.png)

```
UPDATE `rjmkx_virtuemart_category_medias` SET `virtuemart_media_id` = '2388' WHERE `rjmkx_virtuemart_category_medias`.`virtuemart_category_id` = 1;
UPDATE `rjmkx_virtuemart_category_medias` SET `virtuemart_media_id` = '2393' WHERE `rjmkx_virtuemart_category_medias`.`virtuemart_category_id` = 2;
UPDATE `rjmkx_virtuemart_category_medias` SET `virtuemart_media_id` = '2389' WHERE `rjmkx_virtuemart_category_medias`.`virtuemart_category_id` = 3;
UPDATE `rjmkx_virtuemart_category_medias` SET `virtuemart_media_id` = '2385' WHERE `rjmkx_virtuemart_category_medias`.`virtuemart_category_id` = 4;
UPDATE `rjmkx_virtuemart_category_medias` SET `virtuemart_media_id` = '2392' WHERE `rjmkx_virtuemart_category_medias`.`virtuemart_category_id` = 5;
UPDATE `rjmkx_virtuemart_category_medias` SET `virtuemart_media_id` = '2390' WHERE `rjmkx_virtuemart_category_medias`.`virtuemart_category_id` = 6;
UPDATE `rjmkx_virtuemart_category_medias` SET `virtuemart_media_id` = '2391' WHERE `rjmkx_virtuemart_category_medias`.`virtuemart_category_id` = 7;
UPDATE `rjmkx_virtuemart_category_medias` SET `virtuemart_media_id` = '2382' WHERE `rjmkx_virtuemart_category_medias`.`virtuemart_category_id` = 8;
UPDATE `rjmkx_virtuemart_category_medias` SET `virtuemart_media_id` = '2383' WHERE `rjmkx_virtuemart_category_medias`.`virtuemart_category_id` = 9;
UPDATE `rjmkx_virtuemart_category_medias` SET `virtuemart_media_id` = '2384' WHERE `rjmkx_virtuemart_category_medias`.`virtuemart_category_id` = 10;
```

### Таблица **\***\_virtuemart_product_prices

Цены на продукты, импортируемые из 1С в таблицу **\***\_virtuemart_product_prices должны иметь значения:

- `virtuemart_shoppergroup_id`=2
- `product_currency`=131

При создании дочерних продуктов для базового продукта из 1С на основании характеристики "Цвет" испльзуются следующие таблицы и значения полей:

### Таблица **\***\_virtuemart_products

**Заносится основной продукт со следующими значениями полей:**

- `virtuemart_product_id` = id товара в 1С
- `product_sku` = артикул в 1С
- `product_parent_id` = 0
- `product_in_stock` = остаток в 1С
- `has_medias` = 1

**Для каждого дочернего продукта:**

- `virtuemart_product_id` = уникальное значение
- `product_sku` = значение родителского product_sku + номер характеристики в 1С
- `product_parent_id` = virtuemart_product_id родительского продукта
- `product_in_stock` = остаток в 1С по этой харакеристике
- ***`has_medias` = 1***

### **\***\_virtuemart_product_categories

- `virtuemart_product_id` = id соответствющего товара в VirtueMart
- `virtuemart_category_id` id категории товара в 1С или **NULL** для дочернего продукта

### Таблица **\***\_virtuemart_products_ru_ru

- `virtuemart_product_id` = id соответствющего товара в VirtueMart
- `product_s_desc` =
  - значение характеристики цвет для дочернего продукта
  - пустое для родительского продукта
- `product_name` = название родительского товара в 1С

### Таблица **\*\*\***\_virtuemart_products_en_gb** должна быть скопирована из **\*****\_virtuemart_products_ru_ru\*\* по окончании операции импортирования

### Таблица **\***\_virtuemart_product_customfields

В эту таблицу для каждого нового **родительского продукта** **virtuemart_product_id** заносится две записи:

1. Первая запись

   1. `virtuemart_product_id`= id соответствющего товара в VirtueMart
   2. `virtuemart_custom_id`=3
   3. `customfield_value`= значение характеристики "Состав"

2. Вторая запись
   1. `virtuemart_product_id`= id соответствющего родительского товара в VirtueMart
   2. `virtuemart_custom_id`=9
   3. `customfield_value`='product_s_desc'
   4. `customfield_params`=withParent="0"|parentOrderable="0"|

**Для дочерних продуктов, в эту таблицу ничего не заносится**
