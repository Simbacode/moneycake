Moneycake
========================

This a mobile money library for PHP.

## Requirements ##
* PHP 5.4.16+
* curl enabled


## Mobile Money Gateways ##
* pesapal 


## How to use ##
This a composer library and hence it is easy to use.

### Using Composer ###

```
require: "simbacode/moneycake": "dev-master"
```
### Git Clone ###
You can as well clone the library in your vendor folder but remember to add this library to autorun.
```
git clone https://github.com/Simbacode/moneycake
```


## Codeigniter PostPesapalDirectOrderV4 Example ##
```php
<?php
$pesapal = new Simbacode\Moneycake\Pesapal(true, "YOUR-KEY", "YOUR-SECRET");

//variables
$amount = $this->data['amount'];
$description = $this->data['description'];
$transaction_id = $this->data['guid'];
$email = $this->data['email'];
$phone_numer = $this->data['phone'];
$first_name = $this->data['firstname'];
$last_name = $this->data['lastname'];
$currency = "UGX";

if (!empty($amount) && !empty($description) && !empty($transaction_id) && !empty($email) && !empty($phone_numer) && !empty($first_name) && !empty($last_name)) {

    $urlredirect = base_url() . "index.php/pesapal/purchaseredirect";

    $resutl = $pesapal->PostPesapalDirectOrderV4($urlredirect, $amount, $currency, $description, "MERCHANT", $transaction_id, $email, $phone_numer, $first_name, $last_name);

    //$this->set('iframe', $pesapal->getOauthRequest());
    ?>
    <iframe src="<?php echo $pesapal->getOauthRequest(); ?>" width="100%" height="700px"  scrolling="no" frameBorder="0">
    <p>Browser unable to load iFrame</p>
    </iframe>

    <?php
}
        
?>
```

## Codeigniter IPN Example ##
```php
<?php
public function IPN() {

        //FROM PESAPAL
        $this->data['view'] = 'backend/querypayments';
        $this->data['viewstatus'] = 'dashboard';


        $reference = null;
        $pesapal_tracking_id = null;
        if (isset($_GET['pesapal_merchant_reference']))
            $reference = $_GET['pesapal_merchant_reference'];
        if (isset($_GET['pesapal_transaction_tracking_id']))
            $pesapal_tracking_id = $_GET['pesapal_transaction_tracking_id'];
        
        $pesapalNotification=$_GET['pesapal_notification_type'];

        $pesapal = new Simbacode\Moneycake\Pesapal(true, "YOUR-KEY", "YOUR-SECRET");


        $pesapal->InstantPaymentNotificationWithCallback($pesapalNotification, $reference, $pesapal_tracking_id, function($status) {

            if ($status != 'PENDING') {

                //JUST UPDATE ANY OF THE STATUS eg INVALID
                if ($this->db->update('pesapal', array('status' => $status), array('reference' => $reference, 'pesapal_tracking_id' => $pesapal_tracking_id))->affected_rows() > 0) {

                    if($status=='COMPLETE'):
                        //FINISH PAYMENTS LOGIC
                    endif;

                    return TRUE;
                };
            } else {

                return FALSE;
            }
        });


        $this->load_view($this->data);
    }
?>
```

