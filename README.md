# Project management simple wallet:
## Functionality:
- add new wallet
- depositing and withdrawing funds
- generate CSV file by command line

## Start project commands:
- get locally repo: ``git clone git@github.com:kkonwinski/wallet.git``
- inside project folder ```docker-compose up -d --build```
```
docker exec -it wallet_web bash 
composer install
```
- change file .example.env to .env paste to change line to 
```
DATABASE_URL="mysql://wallet:wallet@192.168.2.3:3306/wallet?serverVersion=5.7"
``` 

- run migrations ```bin/console doctrine:migrations:migrate```
- run fixtures ```bin/console doctrine:fixtures:load```

## Example endpoints:
- create wallet 
```
curl --location --request POST 'http://192.168.2.2/wallet/create'
```
- update wallet
```
curl --location --request POST 'http://192.168.2.2/wallet/updateWallet' \
  --form 'walletId="1"' \
  --form 'amount="1"' \
  --form 'transactionType="salary"'
  ```
- show funds on wallet 
```
curl --location --request GET 'http://192.168.2.2/wallet/showFundsWallet/1
```
## Command to generate CSV report specify wallet:
```
bin/console transaction:generate-report
```

## Tech:
- PHP 7.4
- MySql 5.7
- Symfony 5.4 LTS
- Docker
