Yii2 DEB
========
Yii2 Double-entry bookkeeping billing extension.

Concepts in russian: https://www.youtube.com/watch?v=zs4VUokFtPQ

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist lexxanderdream/yii2-deb "dev-master"
```

or add

```
"lexxanderdream/yii2-deb": "dev-master"
```

to the require section of your `composer.json` file.


Run migrations
```
yii migrate --migrationPath=@vendor/lexxanderdream/yii2-deb/migrations --interactive=0
```

Usage
-----

**Basic usage:**

```php
use lexxanderdream\deb\Account;
use lexxanderdream\deb\Transaction;

$senderAccount = Account::get('USER', 1);
$receiverAccount = Acccount::get('USER', 2);

// Transaction is the main concept. It describes one business operation in your system.
$transaction = new Transaction();
$transaction->exec($senderAccount, $receiverAccount, 1000);

echo $senderAccount->amount;
echo $receiverAccount->amount;
```

**or with behavior:**

```php
use lexxanderdream\deb\AccountableBehavior;
use lexxanderdream\deb\Account;

/**
* @property Account $account
*/
class User extends ActiveRecord
{
    ...
    public function behaviors()
    {
        return [
            [
                'class' => AccountableBehavior::className()
                // optional account name for CRUD
                'name' => function() {
                    return $this->username;
                }
            ]
        ];
    }
}

// default system account
$senderAccount = Account::get();

// through model behavior
$receiverAccount = User::findOne(1)->account

$transaction = new Transaction();
$transaction->exec($senderAccount, $receiverAccount, 1000);
```

**Using custom transaction type:**

You can save additional data associated with current transaction in database
```php
use lexxanderdream\deb\Transaction;
use lexxanderdream\deb\Account;

class CustomTransaction extends Transaction
{
    const TITLE = 'Optional transaction description for CRUD';
    
    public $someData1;
    
    public $someData2;
}

$transaction = new CustomTransaction(['someData1' => 'value', 'someData2' => 1]);
$transaction->exec(Account::get(), User::findOne(1)->account, 1000);
```

You can use ActiveRecord model associated with transaction
```php
use lexxanderdream\deb\ActiveRecordTransaction;
use lexxanderdream\deb\Account;

class Purcahse extends ActiveRecord
{
    ...
}

// It's strongly recommended to create your own unique transaction class for each transaction type
class PurchaseProductTransaction extends ActiveRecordTransaction { }

$purchase = new Purchase();
$purchase->market = 'AppStore';
$purchase->marketTransactionId = 'XXXX-XXXX';
$purchase->receipt = 'XXXXX-XXXX-XXXX';
$purchase->productId = 1;
$purchase->save();

$transaction = new PurchaseProductTransaction($purchase);
$transaction->bill(Account::get(), User::findOne(1)->account, 1000);
```

Retrieve custom data from transaction:
```php
$transaction = Transaction::findOne(1);

// display data (if transaction type is CustomTransaction)
echo $transaction->someData1;

// or get associated model (for transaction type extended from ActiveRecordTransaction)
$purchase = $transaction->model;
```
More complex example:
```php
class PurchaseProductTransaction extends ActiveRecordTransaction
{
    // strong typing
    public function __construct(Purchase $purchase)
    {
        parent::__construct($purchase);
    }
    
    /**
     * @return Purchase
     */
    public function getPurchase()
    {
        return $this->getModel();
    }
}

/* @var PurchaseProductTransaction $transaction */
$transaction = Transaction::findOne(1);

$purchase = $transaction->purchase;
echo $purchase->productId;
```

**Account types**

You can also use account types. So one entity can has more than one account:
```php
use lexxanderdream\deb\Account;

const TYPE_CUSTOM = 1;

$mainUserAccount = User::findOne(1)->getAccount(Account::TYPE_MAIN);
$customUserAccount = User::findOne(1)->getAccount(TYPE_CUSTOM);

// system account by type
$customSystemAccount = Account::get(null, null, TYPE_CUSTOM);

// or you can create your custom system account
class CustomSystemAccount extends Account
{
    const TYPE_CUSTOM = 1;
    
    public static getInstance()
    {
        return self::get(null, null, self::TYPE_CUSTOM)
    }
}

$customSystemAccount2 = CustomSystemAccount::getInstance();
```

Management
----------
To enable CRUD you must setup Deb module in your config file (main.php)
```php
...
'modules' => [
    ...
    'deb' => [
        'class' => 'lexxanderdream\deb\Module',
    ]
    ...
],
...
```

Then goto url ``/index.php?r=deb/transaction``

