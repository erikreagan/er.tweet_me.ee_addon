<?php

/**
 * ER Tweet Me
 * 
 * This file must be placed in the
 * /system/plugins/ folder in your ExpressionEngine installation.
 *
 * @package ERTweetMe
 * @version 0.1.0
 * @author Erik Reagan http://erikreagan.com
 * @copyright Copyright (c) 2009 Erik Reagan
 * @see http://erikreagan.com/projects/er_tweet_me/
 */

$plugin_info       = array(
   'pi_name'        => 'ER Tweet Me',
   'pi_version'     => '0.1.0',
   'pi_author'      => 'Erik Reagan',
   'pi_author_url'  => 'http://erikreagan.com',
   'pi_description' => 'Automatically links up twitter user timeline links and hash searches',
   'pi_usage'       => Er_tweet_me::usage()
   );

class Er_tweet_me
{

   var $return_data  = "";

   function Er_tweet_me()
   {
      global $TMPL, $REGX;

      $data = ($TMPL->fetch_param('data') != '') ? ' '.$REGX->unhtmlentities($TMPL->fetch_param('data')) : $REGX->unhtmlentities($TMPL->tagdata) ;
      
      $patterns = array(
         '/(?<=\s)@([a-zA-Z0-9_]+)/',
         '/(?<=\s)#(\S+)/'
         );
      
      $replacements = array(
         '<a href="http://twitter.com/$1" title="$0\'s Twitter Timeline">$0</a>',
         '<a href="http://twitter.com/search?q=%23$1" title="Search $0 on Twitter">$0</a>'
         );
      
      $this->return_data = preg_replace($patterns,$replacements,$data);

   }

   /**
    * Plugin Usage
    * @see ['pi_usage']
    */

   // This function describes how the plugin is used.
   //  Make sure and use output buffering

   function usage()
   {
      ob_start(); 
?>
Simple Use:
========================
To turn a single name or hash into a link just use the single tag like this:

{exp:er_tweet_me data="@erikreagan"}

{exp:er_tweet_me data="#expressionengine"}


More Extensive:
========================
To convert all occurrences of @name or #hashtag wrap the contents in a tag pair like this:

{exp:er_tweet_me}
{body}
{/exp:er_tweet_me}


<?php
      $buffer         = ob_get_contents();

      ob_end_clean(); 

      return $buffer;
   }
   // END

}

/* End of file pi.er_tweet_me.php */
/* Location: ./system/plugins/pi.er_er_tweet_me.php */