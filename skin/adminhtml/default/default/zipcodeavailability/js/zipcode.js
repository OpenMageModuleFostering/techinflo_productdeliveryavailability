$jq = jQuery.noConflict();
$jq(document).ready(function(){
	$jq('#zipcode_tempalte').change(function(){ 
		var sval = $jq("#zipcode_tempalte option:selected").val();
		var curl = $jq("#zipcodectrl").val();
		var productid = $jq('#zipcode_product').val();
		
		$jq.ajax({
			url: curl,
			type: "POST",
			data: {zone:sval, product:productid},
			success: function(result){
				$jq('#addnewzone tbody').append(result);
				$jq('#newzones').slideDown('slow');
			}
		});
	});
});

function rmvProductTempalte(zone)
{
	var removeurl = $jq('#removeZoneURl').val();
	var rproductid = $jq('#zipcode_product').val();
	var zone_id = zone;
	$jq.ajax({
			url: removeurl,
			type: "POST",
			data: {zoneid:zone_id, product:rproductid},
			success: function(result){
				alert(result);
			}
	});
}

function updateTempalte(id)
{	alert("You want To update!");
	var Id = id; 
	$jq('.show_'+Id).hide();
	$jq('.edit_'+Id).show();
}

function saveTempalte(zoneid)
{	var Id = zoneid;	
	var city = $jq('#update_city_'+Id).val();
	var state = $jq('#update_state_'+Id).val();
	var zipcode = $jq('#update_zipcode_'+Id).val();
	var exp_zip = $jq('#update_expZip_'+Id).val();
	
	var removeurl = $jq('#updateZoneURl').val();
	var pid = $jq('#zipcode_product').val();	
	$jq.ajax({
			url: removeurl,
			type: "POST",
			data: {zoneid:Id, product:pid, city:city, ustate: state, upzipcode: zipcode, upexpzip: exp_zip },
			success: function(result){
				var resp_result = $jq.trim(result);
				res = resp_result.split("#");
				console.log(res);
				if(res[3] !=""){ $jq('#show_state_'+Id).html(res[3]); }
				if(res[4] !=""){ $jq('#show_city_'+Id).html(res[4]); }
				if(res[5] !=""){ $jq('#show_zipcode_'+Id).html(res[5]); }
				if(res[6] !=""){ $jq('#show_expZip_'+Id).html(res[6]); }
				$jq('.edit_'+Id).hide();
				$jq('.show_'+Id).slideDown();
			}
	}); 
}