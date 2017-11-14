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

echo $senderAccount->amount;
echo $receiverAccount->amount;
```

**or with behavior:**

```php
use lexxanderdream\deb\AccountableBehavior;
use lexxanderdream\deb\SystemAccount;

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
```

**Using custom transaction type:**

You can save additional data associated with current transaction in database
```php
use lexxanderdream\deb\Transaction;
use lexxanderdream\deb\SystemAccount;

class CustomTransaction extends Transaction
{
    public $someData1;
    
    public $someData2;
}

$transaction = new CustomTransaction(['someData1' => 'value', 'someData2' => 1]);
$transaction->bill(SystemAccount::getInstance(), User::findOne(1)->account, 1000);
```

You can use ActiveRecord model associated with transaction
```php
use lexxanderdream\deb\ActiveRecordTransaction;
use lexxanderdream\deb\SystemAccount;

class PurchaseProductTransaction extends ActiveRecordTransaction
{
    
}

$purchase = new Purchase();
$purchase->market = 'AppStore';
$purchase->marketTransactionId = 'XXXX-XXXX';
$purchase->receipt = 'XXXXX-XXXX-XXXX';
$purchase->productId = 1;
$purchase->save();

$transaction = new PurchaseProductTransaction($purchase);
$transaction->bill(SystemAccount::getInstance(), User::findOne(1)->account, 1000);
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

**Account types**:
You can also use account types. So one entity can has more than one account:
```php
const TYPE_CUSTOM = 1;

$account1 = User::findOne(1)->getAccount(Account::TYPE_MAIN);
$account2 = User::findOne(1)->getAccount(TYPE_CUSTOM);
```
