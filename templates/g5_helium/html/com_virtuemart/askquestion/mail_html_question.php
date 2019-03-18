<?php
defined('_JEXEC') or die('');
/**
* Renders the email for ask a question
 * @package	VirtueMart
 * @subpackage product details
 * @author Maik Künnemann
 * @link https://virtuemart.net
 * @copyright Copyright (c) 2004 - 2018 Virtuemart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: mail_html_question.php 9927 2018-09-10 STS $
 */
?>

<?php
$menuItemID = shopFunctionsF::getMenuItemId(vmLanguage::getLanguage()->getTag());
$product_link = JURI::root() . 'index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&Itemid=' . $menuItemID;
?>

<html>

  <head>
    <style type="text/css">
    body,
    td,
    span,
    p,
    th {
      font-size: 12px;
      font-family: arial, helvetica, sans-serif;
    }

    table,
    tr,
    td {
      border: 0;
      background: #fff;
    }

    table.html-email {
      margin: 10px auto;
      background: #fff;
      #dad8d8 1px;
    }

    h2 {
      font-size: 1.6em;
      font-weight: normal;
      margin: 0px;
      padding: 0px;
    }

    img {
      border: none;
    }

    a {
      text-decoration: none;
      color: #000;
    }

    a.product-details,
    a.product-details:visited {
      border: solid #CAC9C9 1px;
      border-radius: 4px;
      -webkit-border-radius: 4px;
      -moz-border-radius: 4px;
      color: #555;
      text-decoration: none;
      padding: 3px 8px 2px 8px;
    }

    a.product-details:hover {
      color: #888;
      background: #f8f8f8;
    }

    ;

    </style>
  </head>

  <body style="background: #F2F2F2;word-wrap: break-word;">
    <div width="100%">
      <table style="margin: auto; width: 600px; border: solid #dad8d8 1px" cellpadding="0" cellspacing="0">
        <tr>
          <td valign="top" align="center" style="padding: 15px 25px;">
            <img src="<?php echo JURI::root() . $this->vendor->images[0]->file_url ?>" />
            <table border="0" cellpadding="0" cellspacing="0" class="html-email" width="100%">

              <tr>
                <td align="center" style="padding: 15px 5px; border-bottom : 1px solid #dad8d8;">
                  <span
                    style="font-size: 14px; font-weight: bold"><?php echo vmText::sprintf('COM_VIRTUEMART_QUESTION_MAIL_FROM', $this->user->name, $this->user->email); ?></span>
                </td>
              </tr>
              <tr>
                <td align="center" style="padding: 15px 5px;">
                  <h2><a
                      href="<?php echo $product_link ?>"><?php echo $this->product->product_name ?>&nbsp;<small><?php echo $this->product->product_s_desc ?></small></a>
                  </h2>
                </td>
              </tr>
              <tr>
                <td align="center" style="padding: 15px 5px; border-bottom : 1px solid #dad8d8;">
                  <span style="font-size: 14px; font-weight: bold"><?php echo $this->comment; ?></span>
                </td>
              </tr>
              <tr>
                <td align="center" style="padding: 15px 5px; border-bottom : 1px solid #dad8d8;">
                  <a
                    href="<?php echo $product_link ?>"><?php echo $this->product->images[0]->displayMediaThumb('', false, '', true, false, true); ?></a>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </div>
  </body>

</html>
