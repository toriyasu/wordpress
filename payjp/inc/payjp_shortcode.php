<?php
//defines the functionality for the location shortcode
class payjp_shortcode{
    public function __construct(){
        add_action('init', array($this,'register_payjp_shortcodes')); //shortcodes
    }

    //location shortcode
    public function register_payjp_shortcodes(){
        add_shortcode('payjp', array($this,'payjp_shortcode_output'));
    }

    //shortcode display
    public function payjp_shortcode_output($atts, $content = '', $tag){
        if (is_user_logged_in() && $_POST["payjp-token"] != null
              && $_POST["amount"] != null && $_POST["post_id"] != null) {
            $current_user = wp_get_current_user();
            $options = get_option("widget_pay_jp_widget");
            $secret_key = $options[2]["secret_key"];
            try {
                 # init payjp
                 \Payjp\Payjp::setApiKey($secret_key);
                 # create charge
                 $res = \Payjp\Charge::create(array(
                      "card" => $_POST["payjp-token"],
                      "amount" => $_POST["amount"],
                      "currency" => "jpy",
                      "metadata" => array("user_id" => $current_user->ID, "post_id" => $_POST["post_id"])
                 ));
                 $payjp = $res->__toArray();

                 # create settlement
                 $post = array(
                   'post_type'    => 'settlement',
                   'post_title'   => date('Y/m/d H:i:s', $payjp["created"]) . " " . $payjp["id"] . " " . $payjp["amount"] . " " . $payjp["currency"],
                   'post_content' => $payjp["metadata"],
                   'post_author'  => $current_user->ID,
                   'post_status'  => 'private',
                   'ping_status'  => 'closed'
                 );
                 $pid = wp_insert_post( $post, $wp_error );
                 update_post_meta($pid, "amount", $_POST["amount"]);
                 update_post_meta($pid, "post_id", $_POST["post_id"]);
            } catch (\Payjp\Error\Base $e) {
                $body = $e->getJsonBody();
                $err  = $body['error'];
                print('Status is:' . $e->getHttpStatus() . "\n");
                print('Type is:' . $err['type'] . "\n");
                print('Code is:' . $err['code'] . "\n");
                return "<p>エラーが発生しました。お手数ですが再度購入処理を行ってください。</p><p>引き落とし処理は実行されておりません。</p><p>エラーが続く場合は大変ご迷惑をおかけしますが、運営者へお問い合わせください。</p>";
            } catch (Exception $e) {
              // Something else happened, completely unrelated to Payjp
                return "<p>エラーが発生しました。大変ご迷惑をおかけしますが、運営者へお問い合わせください。</p>";
            }
            # create settlement
            return "<p>ご購入ありがとうございました。</p><p>1営業日以内に購入頂いた映像のダウンロードリンクを、ご登録されたメールアドレスへ送付させていただきます。</p>";
        } else {
            return "<p>無効なリクエストです。</p>";
        }
    }

}
# init shortcode
$payjp_shortcode = new payjp_shortcode;
