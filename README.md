Yii2 DEB
========
Yii2 Double-entry bookkeeping billing extension

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist lexxanderdream/yii2-deb "*"
```

or add

```
"lexxanderdream/yii2-deb": "*"
```

to the require section of your `composer.json` file.


Run migrations
```
yii migrate --migrationPath=@lexxanderdream/yii2-deb/migrations --interactive=0
```

Usage
-----

**Basic usage:**

```php
$senderAccount = Account::get('USER', 1);
$receiverAccount = Acccount::get('SYSTEM');

$transaction = new Transaction();
$transaction->bill($senderAccount, $receiverAccount, 1000);
```

**or with behavior:**

```php
use lexxanderdream\deb\behaviors\AccountableBehavior;

class User extends ActiveRecord
{
    ...
    public function behaviors()
    {
        return [
            [
                'class' => AccountableBehavior::className()
            ]
        ];
    }
}

$transaction = new Transaction();
$transaction->bill(SystemAccount::getInstance(), User::findOne(1)->account, 1000);

// display account balance
echo User::findOne(1)->account->amount;
```

**Using custom transaction type:**

```php
use lexxanderdream\deb\models\Transaction;

class CustomTransaction extends Transaction
{
    public $someData;
}

$transaction = new CustomTransaction(['someData' => 'value']);
$transaction->bill(SystemAccount::getInstance(), User::findOne(1)->account, 1000);
```