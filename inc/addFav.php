<?php
// pages currently using this:

?>

<!-- Add Modal -->
<div class="modal fade" id="addFavModal" tabindex="-1" role="dialog" aria-labelledby="addFavModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h5>Are You Sure You Want to Favorite This Restaurant?</h5>
      </div>
      <div class="modal-footer">
        <button name="cancel" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button id="addFav" class="btn btn-info" data-dismiss="modal">Add</button>
      </div>
    </div>
  </div>
</div>

<!-- Success Modal -->
<div id="successfulAddFavModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Added Favorite</h4>
      </div>
      <div class="modal-body">
        <p>Restaurant has been added to your favorite's list</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
      $('#addFavModal').on('show.bs.modal', function(e) {
          var data = $(e.relatedTarget).data();
          $('#addFav', this).data('id', data.id);
      });
      $('#addFav').on('click', function(e){
          var data = $('#addFav').data('id');
          $.ajax({
              type : 'post',
              url: 'inc/functions/addfav.php',
              data: {'id' : data},
              success : function(dataReturned){
                $('#successfulAddFavModal').modal('show');
                //console.log(dataReturned);
              }
          });
      });
      $('#successfulAddFavModal').on('hidden.bs.modal', function () {
          location.reload();
      });
    });
</script>
