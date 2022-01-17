@if(Session::has('success'))
<div class="alert alert-success">
  <strong>Success!</strong> {{session('success')}}.
</div>
@endif
@if(Session::has('warning'))
  <div class="alert alert-warning">
    <strong>Warning!</strong> {{session('warning')}}.
  </div>
@endif

@if(Session::has('warning-pop'))
  <div class="modal" id="warningPopModal" tabindex="-1" role="dialog" aria-labelledby="warningPopModalLabel"
  aria-hidden="true" style="display:block;">
      <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">Warning Popup</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                <p class="text-danger"><b>{{session('warning-pop')}}</b></p>
              </div>
              <div class="modal-footer">
                {{Form::button(trans('common.close_btn_txt'),["class"=>"btn btn-secondary","data-dismiss"=>"modal"])}}
              </div>
          </div>
      </div>
  </div>
  @section('myScripts2')
  <script type="text/javascript">
  $(document).ready(function(){
    $('#warningPopModal').modal('show');
  });
  </script>
  @endsection
@endif
