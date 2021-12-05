# ISTC

Bir sipariş RESTful Api servisidir.

## Docker

- Nginx: 1.21.4
- Php: 7.4.20-fpm
- Mysql: 8.0.27

## Kurulum

Eğer docker yoksa; https://docs.docker.com/ adresinden docker için gerekli kurulumları yapabilirsiniz.

Repoyu indirdikten sonra.
```
cd ~/ISTC/
docker-compose up
```
> Docker containerlarımızı hazır hale getiriyoruz.
> - Nginx web sunucumuz 8000 üzerinden yayın yapıyor.
> - Mysql veritabanı sunucumuz 3307 üzerinden yayın yapıyor.

## RESTful Api

### 1. Sipariş Listeleme

| Tip | Değer |
| --- | --- |
| Method | GET |
| Route | /api/order |

##### Örnek Curl İsteği
```
curl --location --request GET 'localhost:8000/api/order' \
--header 'Content-Type: application/json'
```

### 2. Sipariş Görüntüleme

| Tip | Değer |
| --- | --- |
| Method | GET |
| Route | /api/order/{orderId} |

##### Örnek Curl İsteği
```
curl --location --request GET 'localhost:8000/api/order/17' \
--header 'Content-Type: application/json'
```

### 3. Sipariş Ekleme

> Not: Ürünlerden stok bilgisini günceller.

| Tip | Değer |
| --- | --- |
| Method | POST |
| Route | /api/order/{customerId} |
| Payload | [{"productId": 100, "quantity": 2}, {"productId": 101, "quantity": 1}, {"productId": 102, "quantity": 2}] |

##### Örnek Curl İsteği
```
curl --location --request POST 'localhost:8000/api/order/1' \
--header 'Content-Type: application/json' \
--data-raw '[
    {"productId": 100, "quantity": 2},
    {"productId": 101, "quantity": 1},
    {"productId": 102, "quantity": 2}
]'
```

### 4. Sipariş Silme

> Not: Ürünlerden stok bilgisini günceller.

| Tip | Değer |
| --- | --- |
| Method | DELETE |
| Route | /api/order/{orderId} |

##### Örnek Curl İsteği
```
curl --location --request DELETE 'localhost:8000/api/order/17' \
--header 'Content-Type: application/json'
```

### 5. İndirim Hesaplama
>İndirim Kuralları
>- Toplam 1000TL ve üzerinde alışveriş yapan bir müşteri, siparişin tamamından %10 indirim kazanır.
>- **2** ID'li kategoriye ait bir üründen 6 adet satın alındığında, bir tanesi ücretsiz olarak verilir.
>- **1** ID'li kategoriden iki veya daha fazla ürün satın alındığında, en ucuz ürüne %20 indirim yapılır.

| Tip | Değer |
| --- | --- |
| Method | GET |
| Route | /api/discount/{orderId} |

##### Örnek Curl İsteği
```
curl --location --request GET 'localhost:8000/api/discount/17' \
--header 'Content-Type: application/json'
```