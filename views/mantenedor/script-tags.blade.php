{!! Html::script( $assetsDir. '/toastr-master/toastr.js') !!}

{!! Html::script( $assetsDir. '/seahorse/seahorse-1.2.js') !!}
{!! Html::script( $assetsDir. '/seahorse/seahorse.jquery-1.2.js') !!}

{!! Html::script( $assetsDir. '/bootstrap-colorpicker/js/bootstrap-colorpicker.js') !!}
<script>
$(function(){
	$('.colorpicker').colorpicker();
});
</script>
@include('DxsRavel::help.scripts')