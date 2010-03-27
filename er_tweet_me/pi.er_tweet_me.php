<?php
ini_set('display_errors',E_ALL);
// $constants = get_defined_constants(TRUE);
// echo "<pre>";
// print_r($constants['user']);
// echo "</pre>";
// exit;

/**
 * ER Tweet Me
 * 
 * This file must be placed in the
 * /system/plugins/ folder in your ExpressionEngine installation.
 *
 * @package ERTweetMe
 * @version 1.1.0
 * @author Erik Reagan, developer http://erikreagan.com 
 * @author Andy Marshall, concept http://www.moogaloo.com 
 * @copyright Copyright (c) 2009 Erik Reagan
 * @see http://erikreagan.com/projects/er-tweet-me/
 * @license http://creativecommons.org/licenses/by-sa/3.0/ Attribution-Share Alike 3.0 Unported
 */

$plugin_info       = array(
   'pi_name'        => 'ER Tweet Me',
   'pi_version'     => '1.1.0',
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
      
      // Run the correct function based on our app version
      if (version_compare(APP_VER, '2', '<'))
      {
         $data_array = $this->tweet_me_one();
      } else {
         $data_array = $this->tweet_me_two();
      }
      
      // First pattern is @names
      // Second pattern is #hashtags
      $patterns = array(
         '/(?<![a-zA-Z0-9])@([a-zA-Z0-9_]+)/',
         '/(?<![a-zA-Z0-9])#([a-zA-Z0-9_]+)/'
         );
      
      $replacements = array(
         '<a href="'.$data_array['base_at_url'].'$1" title="$0\'s on Twitter">$0</a>',
         '<a href="'.$data_array['base_hash_url'].'$1" title="Search for $0">$0</a>'
         );
      
      // Replace all matches and return the data
      $this->return_data = preg_replace($patterns,$replacements,$data_array['tag_data']);

   }
   
   
   /**
    * EE 1.6.x version execution
    *
    * @return     string
    */
   function tweet_me_one()
   {
      
      global $TMPL, $REGX;

      // I'm going to add a space to the front of the 'data' parameter so I don't have
      // to write a crazy regular expression to get the job done. This is just to single 
      // out the strings that are twitter-related and not email addresses or div id links
      // we don't want to process things like erik@erikreagan.com or <a href="#comments">
      $tag_data = ($TMPL->fetch_param('data') != '') ? ' '.$REGX->unhtmlentities($TMPL->fetch_param('data')) : $REGX->unhtmlentities($TMPL->tagdata) ;
      $base_at_url = ($TMPL->fetch_param('base_at_url') != '') ? $TMPL->fetch_param('base_at_url') : 'http://twitter.com/' ;
      $base_hash_url = ($TMPL->fetch_param('base_hash_url') != '') ? $TMPL->fetch_param('base_hash_url') : 'http://twitter.com/search?q=%23' ;
      
      return array(
         'tag_data' => $tag_data,
         'base_at_url' => $base_at_url,
         'base_hash_url' => $base_hash_url
         );
      
   }
   
   
   /**
    * EE 2.x version execution
    *
    * @return     string
    */
   function tweet_me_two()
   {
      
      $this->EE =& get_instance();
      // Directly load the typography helper from CI
      require BASEPATH . 'helpers/typography_helper' . EXT;
      
      // I'm going to add a space to the front of the 'data' parameter so I don't have
      // to write a crazy regular expression to get the job done. This is just to single 
      // out the strings that are twitter-related and not email addresses or div id links
      // we don't want to process things like erik@erikreagan.com or <a href="#comments">
      $tag_data = ($this->EE->TMPL->fetch_param('data') != '') ? ' '.entity_decode($this->EE->TMPL->fetch_param('data')) : entity_decode($this->EE->TMPL->tagdata) ;
      $base_at_url = ($this->EE->TMPL->fetch_param('base_at_url') != '') ? $this->EE->TMPL->fetch_param('base_at_url') : 'http://twitter.com/' ;
      $base_hash_url = ($this->EE->TMPL->fetch_param('base_hash_url') != '') ? $this->EE->TMPL->fetch_param('base_hash_url') : 'http://twitter.com/search?q=%23' ;
      
      return array(
         'tag_data' => $tag_data,
         'base_at_url' => $base_at_url,
         'base_hash_url' => $base_hash_url
         );
      
   }


   

   /**
    * Plugin Usage
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


Changing the URL
========================
If you prefer to use a website other than twitter you can specify the base URL like this:

{exp:er_tweet_me base_at_url="http://twittercounter.com/" base_hash_url="http://tweefind.com/queryresults.php?q="}
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