@extends('frontend.index')
@section('page-styles')
    <style>
        .modern-btn {
            background: #868e96;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 20px;
            font-size: 1.1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }
        .modern-btn:hover {
            background: linear-gradient(90deg, #1e2066, #4a4cb3);
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(47, 49, 148, 0.4);
            color: white;
        }
        .modern-btn:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(47, 49, 148, 0.5);
        }
        .col-md-4 {
            margin-bottom: 1rem;
        }
    </style>
@stop
@section('content')
      <section class="breadcrumbs-custom">
          <div class="parallax-container" data-parallax-img="{{asset('images/outlets.jpg')}}">
          <div class="breadcrumbs-custom-body parallax-content context-dark" style="min-height: 20px">
            <div class="container">
              <h2 class="breadcrumbs-custom-title">Our Outlets</h2>
            </div>
          </div>
        </div>
        <div class="breadcrumbs-custom-footer">
          <div class="container">
            <ul class="breadcrumbs-custom-path">
              <li><a href="{{route('home')}}">Home</a></li>
                <li><a href="{{route('outlets')}}">Outlets</a></li>
              <li class="active"> </li>
            </ul>
          </div>
        </div>
      </section>
      @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if($errors->any())
          <div class="alert alert-danger">
              <ul>
                  @foreach($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
      @endif
      <!-- Get in touch-->
      <section class="section section-xl bg-default text-md-start pt-5">
        <div class="container">
            <div class="mb-5">
                {{--<h3 class="wow fadeScale text-center">Our Outlets</h3>
                <hr class="mt-5 mb-5" style="border-top: 2px solid #333; opacity: 1; width: 100%;">--}}
                <div id="faqAccordion">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2">
                                Joshimuddin
                            </button>
                            <div class="collapse mt-2" id="faq2" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    Sector-3, Road-2, H-6, Joshimuddin Road, Uttara. Contact: 01955-578845
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">
                                House Building
                            </button>
                            <div class="collapse mt-2" id="faq3" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    House: 18, Plot-18 Sonargaon Avenue Sector-9, Uttara, Dhaka. Contact: 01955-578802
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="false" aria-controls="faq4">
                                Ajompur
                            </button>
                            <div class="collapse mt-2" id="faq4" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    House-20, Sector-7, Rabindro Sorani, Uttara, Dhaka-1230. Contact: 01955-578838
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq5" aria-expanded="false" aria-controls="faq5">
                                Boshundhara
                            </button>
                            <div class="collapse mt-2" id="faq5" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    Kazi Abdul Ltif Mansion, House: 86 KA-9/A Jagannathpur Badda, Dhaka. Contact: 01955-578806
                                </div>
                            </div>
                        </div>
                        {{--<div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq6" aria-expanded="false" aria-controls="faq6">
                                100 Feet
                            </button>
                            <div class="collapse mt-2" id="faq6" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    Syed Nagar, RS Aria, House 15/19/5, 100 Feet Madani Avenue, Syed Nagar Vatara. Contact: 01955-578834
                                </div>
                            </div>
                        </div>--}}
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq7" aria-expanded="false" aria-controls="faq7">
                                Gulshan -1
                            </button>
                            <div class="collapse mt-2" id="faq7" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    42 Gulshan Avenue Jabbar Tower, Gulshan -1. Contact: 01955-578803
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq8" aria-expanded="false" aria-controls="faq8">
                                D.C.C
                            </button>
                            <div class="collapse mt-2" id="faq8" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    Shop No-G23, DCC, North Super Market, Gulshan -2. Contact: 01955-578804
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq9" aria-expanded="false" aria-controls="faq9">
                                Banani
                            </button>
                            <div class="collapse mt-2" id="faq9" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    46, Kamal Ataturk Avenue, Banani, Dhaka. Contact: 01955-578805
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq10" aria-expanded="false" aria-controls="faq10">
                                Bonosree
                            </button>
                            <div class="collapse mt-2" id="faq10" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    House-15, Road-5, Block-C, Bonosree Project, Dhaka. Contact: 01955-578807
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq11" aria-expanded="false" aria-controls="faq11">
                                Meradia
                            </button>
                            <div class="collapse mt-2" id="faq11" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    Meradia, Plot#L/1/1 & L/2, Road #09, Block#L, Main Road, South Bonosree, Khilgaon, Dhaka-1229. Contact: 01955-578809
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq12" aria-expanded="false" aria-controls="faq12">
                                Khilgaon
                            </button>
                            <div class="collapse mt-2" id="faq12" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    567/A, Block-C, Khilgaon, Taltola, Dhaka. Contact: 01955-578810
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq13" aria-expanded="false" aria-controls="faq13">
                                Pollobi
                            </button>
                            <div class="collapse mt-2" id="faq13" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    Plot-40, Block-B, Road-3, Sector-12, Mirpur, Dhaka. Contact: 01955-578820
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq14" aria-expanded="false" aria-controls="faq14">
                                Kafrul
                            </button>
                            <div class="collapse mt-2" id="faq14" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    28/A, North Kafrul, Dhaka Cantonment-1206. Contact: 01955-578836
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq15" aria-expanded="false" aria-controls="faq15">
                                Shewrapara
                            </button>
                            <div class="collapse mt-2" id="faq15" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    East Shewrapara, Mirpur 847, Lima Complex, Metro Pilar: 317, Dhaka-1216. Contact: 01955-578819
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq16" aria-expanded="false" aria-controls="faq16">
                                Mirpur 1
                            </button>
                            <div class="collapse mt-2" id="faq16" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    10 No, Darus Salam Road South Bishi, Mirpur-1, Dhaka-1216. Contact: 01955-578821
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq17" aria-expanded="false" aria-controls="faq17">
                                Mirpur 10
                            </button>
                            <div class="collapse mt-2" id="faq17" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    Upoma Plaza, Sec#6, Block#A, Road#4, House#2/A, Dhaka-1216. Contact: 01955-578842
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq18" aria-expanded="false" aria-controls="faq18">
                                Senpara
                            </button>
                            <div class="collapse mt-2" id="faq18" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    142/1, Begum Rokeya Soroni, Senpara Parbota. Contact: 01955-578843
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq19" aria-expanded="false" aria-controls="faq19">
                                Shadhin Market
                            </button>
                            <div class="collapse mt-2" id="faq19" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    Shadhin Market, Mirpur Sony Square Opposite. Contact: 01955-578823
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq20" aria-expanded="false" aria-controls="faq20">
                                Ring Road
                            </button>
                            <div class="collapse mt-2" id="faq20" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    Krishi Market Mosque Complex Shop-02, Mohammadpur, Dhaka. Contact: 01955-578818
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq21" aria-expanded="false" aria-controls="faq21">
                                Tajmohol Road
                            </button>
                            <div class="collapse mt-2" id="faq21" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    22/12 Block-C, Tajmohol Road, Mohammadpur, Dhaka-1207. Contact: 01955-578817
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq22" aria-expanded="false" aria-controls="faq22">
                                Mohammadia Housing
                            </button>
                            <div class="collapse mt-2" id="faq22" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    Road#03, House#21, Mohammadia Housing Society, Mohammadpur, Dhaka. Contact: 01955-578847
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq23" aria-expanded="false" aria-controls="faq23">
                                Dhanmondi
                            </button>
                            <div class="collapse mt-2" id="faq23" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    Emperial Amin Ahmed Center, House#54, Road#10/A, Dhanmondi, Dhaka-1209. Contact: 01955-578816
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq24" aria-expanded="false" aria-controls="faq24">
                                Panthopath
                            </button>
                            <div class="collapse mt-2" id="faq24" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    20/3, Sundhoram Plaza, West Panthopath, Dhaka-1205. Contact: 01955-578850
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq25" aria-expanded="false" aria-controls="faq25">
                                Lalbag
                            </button>
                            <div class="collapse mt-2" id="faq25" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    03 No Dhakessori Road, Lalbag, Dhaka-1211. Contact: 01955-578839
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq26" aria-expanded="false" aria-controls="faq26">
                                Elephent Road
                            </button>
                            <div class="collapse mt-2" id="faq26" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    65 New Elephent Road. Contact: 01955-578841
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq27" aria-expanded="false" aria-controls="faq27">
                                Laxmibazar
                            </button>
                            <div class="collapse mt-2" id="faq27" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    24, Shuvash Bosh Avenue, Luxmibazar, Dhaka-1100. Contact: 01955-578812
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq28" aria-expanded="false" aria-controls="faq28">
                                Wari
                            </button>
                            <div class="collapse mt-2" id="faq28" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    11, Ranking Street, Wari, Dhaka. Contact: 01955-578811
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq29" aria-expanded="false" aria-controls="faq29">
                                Baily Road
                            </button>
                            <div class="collapse mt-2" id="faq29" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    137 New Baily Road, Dhaka. Contact: 01955-578808
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq30" aria-expanded="false" aria-controls="faq30">
                                Keranigonj
                            </button>
                            <div class="collapse mt-2" id="faq30" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    Samanti Tower, 2nd Floor, Kadamtoli Aganogar, South Keraniganj, Dhaka-1310. Contact: 01955-578833
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq31" aria-expanded="false" aria-controls="faq31">
                                Savar
                            </button>
                            <div class="collapse mt-2" id="faq31" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    House#3, Shop#244, 1st Floor, Savar, Dhaka-1340. Contact: 01955-578835
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#faq32" aria-expanded="false" aria-controls="faq32">
                                Targas
                            </button>
                            <div class="collapse mt-2" id="faq32" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    Plot-2/1, Shape No 1, Kunia Pasor Tagas, Ward No-37, Gazipur. Contact: 01955-578851
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </section>
@endsection
