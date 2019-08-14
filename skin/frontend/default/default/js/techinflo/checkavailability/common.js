  function hideAll() {
        var cb = jQuery.noConflict();
        cb("#opc-shipping").hide();
        cb("#opc-shipping_method").hide();
        cb("#opc-payment").hide();
        cb("#opc-review").hide();
    }
    function showall() {
        cb("#opc-shipping").show();
        cb("#opc-shipping_method").show();
        cb("#opc-payment").show();
        cb("#opc-review").show();
    } 
    function setCookie(c_name, value, exdays)
    {
        var exdate = new Date();
        exdate.setDate(exdate.getDate() + exdays);
        var c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
        document.cookie = c_name + "=" + c_value + ";path=/";
    }
     function chkavail_checkout(pintext,url,id) {
        var cb = jQuery.noConflict();
        cb("#resp").html('');
        //var id = '<?php echo serialize($ids); ?>';
        if (pintext == 'undefined' || pintext == '') {
            var pintext = cb("#checkpin").val();
        }
        url = url + 'id/' + id + '/pincode/' + pintext;
        cb("#loader").show();
        cb("#ckhbtn").hide();

        cb.ajax({url: url, success: function(result) {
                var resp_result = cb.trim(result);
                //alert(resp_result);
                var html = "";
                res = resp_result.split(",");
                if (res[0] == '1' && res[1] != '') {
                    setCookie("avl_pin_code", '', 1);
                    cb("#loader").hide();
                    cb("#ckhbtn").show();
                    cb("#location_msg").show();
                    html = 'There are items in your cart that cannot be shipped to your location.';
                    cb("#location_msg").html(html);



                    //cb(".col-2").html("<?php echo $name; ?>");
                    cb("#avl_status").html("<?php echo $name; ?>");
                    cb("#avl_status").show();
                    /* cb(".col-2").hide(); */

                    hideAll();

                    cb(".col-main *").off('click');

                    cb("#location_msg_avl").hide();
                    setCookie("pin_code", res[1], 1);
                    //cb("#checkpin").val(res[1]);
                    var response = "<span class='resp' style='color:red'>Not available in your location yet.</span>";

                } else {
                    cb("#co_order_items_not_allowed").html('');
                    cb("#avl_status").hide();
                    cb("#location_msg").hide();
                    showall();
                    cb(".col-main *").on('click');
                    setCookie("pin_code", '', 1);
                    setCookie("avl_pin_code", result, 1);

                    if (cb("#checkpin").val() == "") {
                        setCookie("pin_code", '', 1);
                        setCookie("avl_pin_code", '', 1);
                        var response = "";
                        cb("#location_msg_avl").hide();
                    } else {
                        //cb(".valzip").val(cb.trim(result));
                        cb(".valship").val(cb.trim(result));
                        cb("#checkpin").val(cb.trim(result));
                        cb("#location_msg_avl").show();
                        cb("#location_msg_avl").text('Available in your location.');
                        var response = "<span class='resp' style='color:green'>Avaliable: " + result + "</span>";
                    }
                    //cb("#checkpin").val(result);
                    // window.location='';
                }
                /*  cb(".check-data").hide();
                 cb("#checkpin").hide(); */

                cb("#resp").html(response);
                cb("#resp").show();
            }});

        return false;
    }<script>
    var cb = jQuery.noConflict();
    cb(document).ready(function() {
        cb("#resp").html('');
        var billpost = document.getElementById('billing:postcode');
        billpost.className += " valzip";
        var shippost = document.getElementById('shipping:postcode');
        shippost.className += " avlship";
        cb("#checkoutSteps").prepend('<div id="avl_status" ></div>');
        cb("#checkoutSteps").prepend('<div style="" id="location_msg" class="unservicable-warning"></div>');

<?php if ((isset($_COOKIE["pin_code"]) && $_COOKIE["pin_code"]!='') ||(isset($_COOKIE["avl_pin_code"]) && $_COOKIE["avl_pin_code"]!="")) { ?>

      
          
    <?php if (isset($_COOKIE["pin_code"]) && $_COOKIE["pin_code"] != '') { ?>
                var pintext = "<?php echo $_COOKIE["pin_code"]; ?>";
					
    <?php } else { ?>
                var pintext = "<?php echo $_COOKIE["avl_pin_code"]; ?>";
				
    <?php } ?>
	<?php	if(!$myStatus){?>
                cb(".valzip").val(pintext);
                cb(".valzip").change();
                cb(".avlship").val(pintext);
                cb(".avlship").change();
    <?php } ?>
    <?php if ($myStatus) { ?>
                var pintext = "<?php echo $zip; ?>";
    <?php } ?>
            chkavail_checkout(pintext);
<?php } ?>
        var remember = document.getElementById('billing:use_for_shipping_yes');

        cb(".valzip").focusout(function() {
            alert('a');
            if (remember.checked) {
                var pin = cb(".valzip").val();
                setTimeout("chkavail(" + pin + "")", 30);
            }
        });
 

        cb(".avlship").focusout(function() {
            var pin = cb(".avlship").val();
           setTimeout("chkavail(" + pin + ")", 30);


        });

    });



    cb('#billing:use_for_shipping_yes').click(function() {
        var remember = document.getElementById('shipping:same_as_billing');
        if (remember.checked) {
            var pin = cb(".valzip").val();
             setTimeout("chkavail(" + pin + ")", 30);
        }
    });

    function chkavail(pintext) {
        var cb = jQuery.noConflict();
        cb("#resp").html('');
        var id = '<?php echo serialize($ids); ?>';
        if (pintext == 'undefined' || pintext == '') {
            var pintext = cb("#checkpin").val();
        }
        url = "<?php echo Mage::getUrl('checkavl/index/cart') ?>" + 'id/' + id + '/pincode/' + pintext;
        cb("#loader").show();
        cb("#ckhbtn").hide();

        cb.ajax({url: url, success: function(result) {
                var resp_result = cb.trim(result);
                //alert(resp_result);
                var html = "";
                res = resp_result.split(",");
                if (res[0] == '1' && res[1] != '') {
                    setCookie("avl_pin_code", '', 1);
                    cb("#loader").hide();
                    cb("#ckhbtn").show();
                    cb("#location_msg").show();
                    html = 'There are items in your cart that cannot be shipped to your location.';
                    cb("#location_msg").html(html);



                    //cb(".col-2").html("<?php echo $name; ?>");
                    cb("#avl_status").html("<?php echo $name; ?>");
                    cb("#avl_status").show();
                    /* cb(".col-2").hide(); */

                    hideAll();

                    cb(".col-main *").off('click');

                    cb("#location_msg_avl").hide();
                    setCookie("pin_code", res[1], 1);
                    //cb("#checkpin").val(res[1]);
                    var response = "<span class='resp' style='color:red'>Not available in your location yet.</span>";

                } else {
                    cb("#co_order_items_not_allowed").html('');
                    cb("#avl_status").hide();
                    cb("#location_msg").hide();
                    showall();
                    cb(".col-main *").on('click');
                    setCookie("pin_code", '', 1);
                    setCookie("avl_pin_code", result, 1);

                    if (cb("#checkpin").val() == "") {
                        setCookie("pin_code", '', 1);
                        setCookie("avl_pin_code", '', 1);
                        var response = "";
                        cb("#location_msg_avl").hide();
                    } else {
                        //cb(".valzip").val(cb.trim(result));
                        cb(".valship").val(cb.trim(result));
                        cb("#checkpin").val(cb.trim(result));
                        cb("#location_msg_avl").show();
                        cb("#location_msg_avl").text('Available in your location.');
                        var response = "<span class='resp' style='color:green'>Avaliable: " + result + "</span>";
                    }
                    //cb("#checkpin").val(result);
                    // window.location='';
                }
                /*  cb(".check-data").hide();
                 cb("#checkpin").hide(); */

                cb("#resp").html(response);
                cb("#resp").show();
            }});

        return false;
    }
    function hideAll() {
        var cb = jQuery.noConflict();
        cb("#opc-shipping").hide();
        cb("#opc-shipping_method").hide();
        cb("#opc-payment").hide();
        cb("#opc-review").hide();
    }
    function showall() {
        cb("#opc-shipping").show();
        cb("#opc-shipping_method").show();
        cb("#opc-payment").show();
        cb("#opc-review").show();
    }
    function setCookie(c_name, value, exdays)
    {
        var exdate = new Date();
        exdate.setDate(exdate.getDate() + exdays);
        var c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
        document.cookie = c_name + "=" + c_value + ";path=/";
    }

    
</script>
