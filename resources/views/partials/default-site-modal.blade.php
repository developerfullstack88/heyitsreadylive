<div class="modal fade" id="defaultSiteModal" tabindex="-1" role="dialog" aria-labelledby="defaultSiteLabel"
aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Default Location</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <p>Do you want to change your default location.</p>
            </div>
            <div class="modal-footer">
              {{Form::button(trans('common.close_btn_txt'),["class"=>"btn btn-secondary","data-dismiss"=>"modal"])}}
              <a href="{{route('sites.index')}}" class="btn btn-secondary">
                Change Location
              </a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="defaultNoSiteModal" tabindex="-1" role="dialog" aria-labelledby="defaultNoSiteLabel"
aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Default Location</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <p>You have not set default location yet.</p>
            </div>
            <div class="modal-footer">
              {{Form::button(trans('common.close_btn_txt'),["class"=>"btn btn-secondary","data-dismiss"=>"modal"])}}
              <a href="{{route('sites.create')}}" class="btn btn-secondary">
                Set Default Location
              </a>
            </div>
        </div>
    </div>
</div>
