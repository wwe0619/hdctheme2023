jQuery(document).ready(function($){
	$("#zip").keyup(function(){
		AjaxZip3.zip2addr(this,'','pref','city');
		//AjaxZip3.zip2addr( '〒上3桁', '〒下4桁', '都道府県', '市区町村' );
	});
});