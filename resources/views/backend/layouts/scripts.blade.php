<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>


    <script src="{{ asset('backend/shards-ui@3.0.0/dist/js/shards.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sharrre/2.0.1/jquery.sharrre.min.js"></script>
    <script src="{{ asset('backend/scripts/extras.1.3.1.min.js') }}"></script>

    <script src="{{ asset('backend/scripts/shards-dashboards.1.3.1.min.js') }}"></script>
    <script src="{{ asset('backend/datatables/1.10.16/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/datatables/responsive/2.2.1/js/dataTables.responsive.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
    <script src="{{asset('backend/scripts/app/app-transaction-history.1.3.1.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script src="{{ asset('backend/scripts/sweetalert2.all.min.js') }}"></script>

    <script>
      @if (Session::has('success'))
        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          onOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
          }
        });

        Toast.fire({icon:'success', title:'{{ Session::get("success") }}'});
      @endif

      @if (Session::has('fail'))
        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true,
          onOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
          }
        });

        Toast.fire({icon:'error', title:'{{ Session::get("fail") }}'});
      @endif



      $(window).on("load", function(){
          if ($("#preloader")[0]) {
              $("#preloader").delay(500).fadeTo(500, 0, function(){
                  $(this).remove();
              });
          }
      });

      $(".custom-file-input").on("change", function(){
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").html(fileName);
      });
    </script>



    @yield('page-script')
