<?php
// pages currently using this:
// - profile.php
// - restaurant.php
?>

<!-- Delete Modal -->
<div class="modal fade" id="deleteReviewModal" tabindex="-1" role="dialog" aria-labelledby="deleteReviewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h5>Are You Sure You Want to Delete Your Review?</h5>
      </div>
      <div class="modal-footer">
        <button name="cancel" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button id="deleteReview" class="btn btn-danger" data-dismiss="modal">Delete</button>
      </div>
    </div>
  </div>
</div>

<!-- Success Modal -->
<div id="successfulDeleteModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Review Deleted</h4>
      </div>
      <div class="modal-body">
        <p>Successfully deleted your review!</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
      // set the id of the review that was initially clicked for modal popup
      $('#deleteReviewModal').on('show.bs.modal', function(e) {
          var data = $(e.relatedTarget).data();
          $('#deleteReview', this).data('id', data.id);
      });
      // on the confirm delete button submit, run ajax
      $('#deleteReview').on('click', function(e){
          // console.log($('#deleteReview').data('id'));
          var data = $('#deleteReview').data('id');
          $.ajax({
              type : 'post',
              url: 'inc/functions/deletereview.php',
              data: {'id' : data},
              success : function(dataReturned){
                $('#successfulDeleteModal').modal('show');
                //console.log(dataReturned);
              }
          });
      });
      // after confirming the successful delete modal, refresh page
      $('#successfulDeleteModal').on('hidden.bs.modal', function () {
          location.reload();
      });
    });
</script>
