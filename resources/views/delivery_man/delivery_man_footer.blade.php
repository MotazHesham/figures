<!-- FOOTER -->
<!--===================================================-->
<footer id="footer" style="padding-top:0px !important">
    <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
    <!-- Remove the class "show-fixed" and "hide-fixed" to make the content always appears. -->
    <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->

	<div class="col-sm-10">
		<p class="pad-lft">&#0169; {{ date('Y') }} {{ \App\Models\GeneralSetting::first()->site_name }} v{{ \App\Models\BusinessSetting::where('type', 'current_version')->first()->value }}</p>
	</div>

</footer>
