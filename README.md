VirtueMart Configuration

To redirect Joomla registrations to VM use
Plugins: VM Framework Loader during Plugin Updates

Цены на продукты, импортируемые из 1С в таблицу rjmkx_virtuemart_product_prices должны иметь значение поля virtuemart_shoppergroup_id=2 и поля product_currency=131

При создании дочерних продуктов (ДП) для базового продукта из 1С на основании характеристики "Цвет" испльзуются следующие таблицы и значения полей:

Таблица rjmkx_virtuemart_products

заносится основной продукт со следующими значениями полей:
virtuemart_product_id = id товара в 1С
product_sku = артикул в 1С
product_parent_id = 0
product_gtin = ''
product_in_stock = остаток в 1С

Для каждого дочернего продукта:
virtuemart_product_id = уникальное значение
product_sku = значение родителского product_sku + номер характеристики в 1С
product_parent_id = virtuemart_product_id родительского продукта
product_in_stock = остаток в 1С по этой харакеристике

Таблица rjmkx_virtuemart_products_ru_ru
virtuemart_product_id = id соответствющего товара в VirtueMart
product_s_desc = знацение характеристики цвет
product_name = название товара (или родительского товара) в 1С
**Заносится основной продукт со следующими значениями полей:**

Таблица rjmkx_virtuemart_products_en_gb должна быть скопирована из rjmkx_virtuemart_products_ru_ru по окончании операции импортирования

Таблица rjmkx_virtuemart_product_customfields

В эту таблицу для каждого нового virtuemart_product_id заносится две записи:

1)
virtuemart_product_id = id соответствющего товара в VirtueMart
virtuemart_custom_id = 3  
customfield_value: значение характеристики "Состав"

2)
virtuemart_product_id = id соответствющего товара в VirtueMart
virtuemart_custom_id = 9  
customfield_value: значение 'product_s_desc'
### rjmkx_virtuemart_product_categories

- virtuemart_product_id = id соответствющего товара в VirtueMart
- virtuemart_category_id id категории товара в 1С или (id категории родительского продукта в VirtueMart ???)

### Таблица rjmkx_virtuemart_products_ru_ru

- virtuemart_product_id = id соответствющего товара в VirtueMart
- product_s_desc =
  - значение характеристики цвет для дочернего продукта
  - название продукта для родительского продукта
- product_name = название родительского товара в 1С

### Таблица **rjmkx_virtuemart_products_en_gb** должна быть скопирована из **rjmkx_virtuemart_products_ru_ru** по окончании операции импортирования

### Таблица rjmkx_virtuemart_product_customfields

В эту таблицу для каждого нового **virtuemart_product_id** заносится две записи:

1. Первая запись

   1. virtuemart_product_id = id соответствющего товара в VirtueMart
   2. virtuemart_custom_id = 3
   3. customfield_value = значение характеристики "Состав"

2. Вторая запись
   1. virtuemart_product_id = id соответствющего товара в VirtueMart
   2. virtuemart_custom_id = 9
   3. customfield_value = 'product_s_desc'
