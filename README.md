Installation
------------
```
git clone https://github.com/robertroginski/git-api ./my-git-api

composer install

npm install
```


###Run application


```
cd my-git-api/

symfony server:start
```

Your application is running at the address: http://localhost:8000/


REST API
------------

### Example
```
curl --location --request GET 'http://localhost:8000/api?first=symfony/symfony&second=laravel/laravel'
```

TODO
------------
* Exception 429 too many requests to GitHub API, actualy limit is 60 requests per hour
