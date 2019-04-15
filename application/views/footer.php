<?php
	if(isset($stop)){
		$stop = number_format($stop, 2, ",", ".");
		echo "Program berhasil dieksekusi dalam waktu $stop detik";
	}
?>
		</div>
	</section>
</main>


<script type="text/javascript" src="<?=base_url()?>js/jquery-1.10.2.min.js"></script>
<!-- <script type="text/javascript" src="<?=base_url()?>js/less-1.3.3.min.js"></script> -->
<script type="text/javascript" src="<?=base_url()?>js/alertify.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/jquery.ui.core.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/jquery.ui.menu.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/jquery.ui.position.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/jquery.ui.autocomplete.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/modernizr.custom.28468.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/less-1.3.3.min.js"></script>
<script type="text/javascript">
$(function(){
	$(".notif").click(function(){
		$(this).next(".notif-container").slideToggle();
		$(this).children(".num").hide();
	});
	$(".first").click(function(){
		$(".submenu, .showmenu").slideUp();
		$(".first").removeClass("active");
		$(this).addClass("active");
		$(this).next(".submenu").slideDown();

	});
	$(".box-error, .box-success, .box-warning, .box-information").click(function(){
		$(this).slideToggle();
	});

	$("#addBtn").click(function(){
		$("#addForm").slideToggle(300);
		$(this).toggleClass("btnActive");
	});

	$(".delete").on('click', function(e){
    e.preventDefault();
    var href = this.href;
    alertify.confirm("Apakah anda benar-benar ingin menghapus data tersebut?", function (e) {
        if (e) {
            window.location.href = href;
        }
    	});

	});

	$("#kdbr").autocomplete({
	    source: function( request, response ) {
	      $.ajax({
	        url: "data/json/kdbr",
	        type: "POST",
	        dataType: "json",
	        data: {term: request.term},
	        success: function(data) {
	          response(data);
	        }
	      });
	    } 
	});
	$("#kdmr").autocomplete({
	    source: function( request, response ) {
	      $.ajax({
	        url: "data/json/kdmr",
	        type: "POST",
	        dataType: "json",
	        data: {term: request.term},
	        success: function(data) {
	          response(data);
	        }
	      });
	    } 
	});
	$("#kdgl").autocomplete({
	    source: function( request, response ) {
	      $.ajax({
	        url: "data/json/kdgl",
	        type: "POST",
	        dataType: "json",
	        data: {term: request.term},
	        success: function(data) {
	          response(data);
	        }
	      });
	    } 
	});
	$("#kdst").autocomplete({
	    source: function( request, response ) {
	      $.ajax({
	        url: "data/json/kdst",
	        type: "POST",
	        dataType: "json",
	        data: {term: request.term},
	        success: function(data) {
	          response(data);
	        }
	      });
	    } 
	});
	$("#kdjn").autocomplete({
	    source: function( request, response ) {
	      $.ajax({
	        url: "data/json/kdjn",
	        type: "POST",
	        dataType: "json",
	        data: {term: request.term},
	        success: function(data) {
	          response(data);
	        }
	      });
	    } 
	});

	$(".bykat").click(function(){
		$(".cont_a").slideDown();
		$(".cont_b").slideUp();
		$(this).addClass("btnActive");
		$(".byspec").removeClass("btnActive");
	});
	$(".byspec").click(function(){
		$(".cont_b").slideDown();
		$(".cont_a").slideUp();
		$(this).addClass("btnActive");
		$(".bykat").removeClass("btnActive");
	});

	 $(".chosen-select").chosen({no_results_text: "Oops, nothing found!"}); 

	<?php
		$this->def->pesan_alertify();
	?>
});
</script>	
</Body>
</html>