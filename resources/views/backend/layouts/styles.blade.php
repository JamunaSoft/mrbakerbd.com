<link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">


    <link rel="stylesheet" href="{{ asset('backend/styles/accents/warning.1.3.1.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/datatables/responsive/2.2.1/css/responsive.dataTables.min.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    @yield('page-style')
    <style>
        #preloader {
          position: fixed;
          z-index: 1111111111111;
          top: 0;
          left: 0;
          display: block;
          width: 100%;
          height: 100%;
          background-color: #F1F3F5;
        }

        #preloader-content {
          width: 200px;
          margin: 0 auto;
          display: block;
          text-align: center;
          top: 50%;
          position: absolute;
          left: 50%;
          -webkit-transform: translate(-50%, -50%) rotate(-13deg);
          -ms-transform: translate(-50%, -50%) rotate(-13deg);
          transform: translate(-50%, -50%) rotate(-13deg);
        }
    </style>
