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
          <div class="parallax-container" data-parallax-img="{{asset('images/about.jpg')}}">
          <div class="breadcrumbs-custom-body parallax-content context-dark" style="min-height: 20px">
            <div class="container">
              <h2 class="breadcrumbs-custom-title">About Us</h2>
            </div>
          </div>
        </div>
        <div class="breadcrumbs-custom-footer">
          <div class="container">
            <ul class="breadcrumbs-custom-path">
              <li><a href="{{route('home')}}">Home</a></li>
                <li><a href="{{route('outlets')}}">About Us</a></li>
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

            <div class="row mb-5">
                <div class="col-md-6">
                    <div class="">

                        <h3>Our Mission</h3>
                        <p>We’re passionate about delivering high-quality, innovative, and mouth-watering baked goods! We aim to uphold the highest professionalism and customer satisfaction standards, ensuring every visit is a delightful experience.</p>
                    </div>


                </div>
                <div class="col-md-6">
                    <div class="">

                        <h3>Our Vision</h3>
                        <p>We aspire to be the top cake and pastry brand in Bangladesh and beyond, known for our commitment to quality and reliability. Our mission is to inspire joy through our delicious creations while pioneering new standards in the bakery industry through teamwork, technology, and trust. Let’s create sweet moments together!</p>
                    </div>
                </div>

            </div>

            {{--<div class="mb-5">
            <h2 class="wow fadeScale mb-5" style="font-size: 40px; font-weight: 500;">About Mr. Baker</h2>
            <div>
                <b>
                    <font color="#424242">Customer focused management team of Mr. Baker is constituted of well experienced professionals who endeavors to maintain top notch quality in every aspect and can make a real difference in the quality of the&nbsp;</font>
                </b>
                <b>
                    <font color="#424242">end products. Mr. Baker always encourages its customers to work together for maximize the confident by sharing views and expertise that they have.&nbsp;</font>
                </b>
                <b>
                    <font color="#424242">SHOP ourvision</font>
                </b>
            </div>
            <div><b>
                    <font color="#424242"><br></font></b>
            </div>
            <div><b >
                    <font color="#424242">To stay ahead in competition, Mr. Baker always forge ahead towards complete customer satisfaction by providing high quality products with mutual benefit,&nbsp;</font>
                </b>
                <b>
                    <font color="#424242">prosperity and success of a long term business relationship. All around activities and team efforts have been taken up to fulfill the vision.</font>
                </b>
            </div>
            <div>
                <b ><font color="#424242"><br></font></b>
            </div>
            <div>
                <b>
                    <font color="#424242">Mr. Baker-Cake &amp; Pastry Shop manufactures made-to-order cake &amp; pastry and supplies rawmaterials for the Bangladeshi market. Founded in 2001&nbsp;</font></b>
                <b>
                    <font color="#424242">Our vision is to help clients: Strengthen brand identity. Reduce costs while improving quality. Scale production without new capital expenses.&nbsp;</font></b><b style="background-color: rgb(247, 247, 247);">
                    <font color="#424242">Our promise to you is this : to manufacture and deliver your order on time, on budget, and at the quality you expect. We keep this promise by leveraging our 'best in class'&nbsp;</font></b>
                <b><font color="#424242">production facilities. This helps clients maintain quality and consistency while minimizing production cost. Our success is derived from seamless execution of our&nbsp;</font></b>
                <b><font color="#424242">core competencies in manufacturing, operations, and customer service.</font></b>
            </div><div><b><font color="#424242"><br></font></b></div>
            <div><b><font color="#424242">Mr. Baker is a well-recognized and highly acclaimed business conglomerate gained its distinctive position in different business areas within short period of time since its incorporation&nbsp;</font></b>
                <b style="background-color: rgb(247, 247, 247);">
                    <font color="#424242">in 2001. Presently Mr. Baker is one of the premier cake &amp; pastry shop in the country. The management committee always strives to explore&nbsp;</font></b>
                <b><font color="#424242">new business opportunities with the help of local and foreign principals. Assessing huge potential business&nbsp; pportunities in cake &amp; pastry arena that&nbsp;</font></b>
                <b><font color="#424242">exist nationally as well internationally, the management committee decides to setup a complete Cake &amp; Pastry shop with full modern&nbsp;</font></b>
                <b><font color="#424242">facilities.&nbsp;</font></b></div><div>
                <b><font color="#424242"><br></font></b></div><div>
                <b><font color="#424242">Mr. Baker is an private limited company, announces with pleasure of begins its business operation in early 2001 in the field of bangladesh. It has already recognized as an innovative,&nbsp;</font></b>
                <b><font color="#424242">responsive, reliable and quality conscious cake &amp; pastry house by delivering quality products.&nbsp;</font></b></div><div>
                <b><font color="#424242"><br></font></b></div><div>
                <b><font color="#424242">a Bangladeshi company which has provided varities Cake &amp; Pastry design and manufacturing services for headgear since 2001. As a manufacturer, we take great pride&nbsp;</font></b>
                <b><font color="#424242">in producing superior quality products. We control every aspects of the manufacturing process - butter, Ghee, Vinegar ; even oils must meet our exacting standards. No product&nbsp;</font></b>
                <b><font color="#424242">is supplied directly to our customers or to a distribution centers until it has passed every one of our quality control inspection procedures. That’s why you can be sure that&nbsp;</font></b>
                <b><font color="#424242">every items we manufacture for you will meet your specifications.&nbsp;</font></b></div><div>
                <b><font color="#424242"><br></font></b></div><div>
                <b><font color="#424242">Our corporate office in Bangladesh is linked directly to any other factory location in China, Malysia, Dubai. We source our materials from Taiwan, Korea, China. and Bangladesh. We maintain a&nbsp;</font></b>
                <b><font color="#424242">stock of all material for cake &amp; pastry manufacturing at our modern ware house. This inventory supports any size production need.&nbsp;</font></b></div><div>
                <b><font color="#424242"><br></font></b></div><div>
                <b><font color="#424242">Mr. Baker has emerged in the industry with a very clear vision, we would always strive to render professional services with high standard of performance and achieve full customer satisfaction.&nbsp;</font></b>
                <b><font color="#424242">We would use the latest technology being practiced in the modern world and ensure high quality performance, which a customer always aspires, To ensure the above, we have&nbsp;</font></b>
                <b><font color="#424242">professional team members who are trained, qualified and experienced in their own profession.&nbsp;</font></b></div><div>
                <b><font color="#424242"><br></font></b></div><div><b style="background-color: rgb(247, 247, 247);"><font color="#424242">Customer confidence is a great factor for a service company, Customer confidence depends on credibility, brand image, financial strength, back up support etc. Mr. Baker enjoys full&nbsp;</font></b>
                <b><font color="#424242">customer confidence because we are committed and we have all the above factors in our favor.&nbsp;</font></b>
                <b><font color="#424242">And&nbsp;&nbsp;</font></b><b style="background-color: rgb(247, 247, 247);"><font color="#424242">We strictly follow all international labor laws and business ethics.&nbsp;</font></b>
            </div>

            </div>--}}

            <div class="row mt-5">




                    <h2 class="wow fadeScale" style="font-size: 40px; font-weight: 500;">Managing Director's Message</h2>
                    <div class="row justify-content-center align-items-center">

                        <div class="row" style="border: 10px solid #ddd; padding: 20px;">
                            <div class="col-sm-12 col-md-8 col-lg-4  wow fadeInRight" style="visibility: visible; animation-name: fadeInRight;">
                                <img src="{{asset('images/md-mrbaker.jpg')}}" alt="Managing Director" class="pt-2"  style="max-height:455px;">
                            </div>
                            <div class="col-sm-12 col-md-8 col-lg-8 wow fadeInRight" style="visibility: visible; animation-name: fadeInRight;">

                                <div class="row justify-content-center align-items-center pt-5">
                                  <p class="text-center">Our journey with Mr. Baker started with a heartfelt belief: every celebration deserves something extraordinary! Since 2002, we've been thrilled to bring the world’s finest cakes to Bangladesh, delighting our customers with quality, creativity, and consistency. We're passionate about ensuring each experience is special, and our dedicated team, state-of-the-art facilities, and innovative spirit have made us a trusted name in the cake and pastry industry. As we look ahead, we are excited to continue serving excellence, crafting with care, and consistently exceeding your expectations. Thank you for being part of our sweet journey!</p>
                                </div>
                                <div class="row justify-content-center align-items-center">
                                    <div class="col-md-2">
                                    </div>
                                    <div class="col-md-8 text-center">
                                        <h6 class="">Mohammad Ullah Dewan</h6>
                                        <p class="mt-0">Managing Director, Mr. Baker</p>
                                    </div>
                                    {{--<div class="col-md-2">
                                        <img src="{{asset('images/signature.png')}}" alt="sign" class="pt-2"  style="max-height:55px;">
                                    </div>--}}
                                    <div class="col-md-2">
                                    </div>
                                </div>
                            </div>

                        </div>


                        {{--<div class="col-sm-8 col-md-4 col-lg-4 wow fadeInLeft" style="visibility: visible; animation-name: fadeInLeft;">
                            <img src="{{asset('images/managing-director.jpeg')}}" alt="Managing Director" class="pt-2"  style="max-height:455px;">
                        </div>

                        <div class="col-md-10 col-lg-8 d-flex flex-column justify-content-center align-items-center">
                            <div class="tabs-custom tabs-jean w-100" id="tabs-1">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="tabs-1-1">
                                        <div class="box-info-creative text-center" >
                                            <div class="box-info-creative-text" style="text-align:justify;">
                                                Our journey with Mr. Baker started with a heartfelt belief: every celebration deserves something extraordinary! Since 2002, we've been thrilled to bring the world’s finest cakes to Bangladesh, delighting our customers with quality, creativity, and consistency. We're passionate about ensuring each experience is special, and our dedicated team, state-of-the-art facilities, and innovative spirit have made us a trusted name in the cake and pastry industry. As we look ahead, we are excited to continue serving excellence, crafting with care, and consistently exceeding your expectations. Thank you for being part of our sweet journey!

                                            </div>
                                            <span class="link-classic box-info-creative-link" style="font-size: 10px; text-align:left">Mohammad Ullah Dewan <br>Managing Director, Mr. Baker</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>--}}
                    </div>
            </div>

        </div>
      </section>
@endsection
