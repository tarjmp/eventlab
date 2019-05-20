@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>{{ __('about.title') }}</h2>
                <br>
                <h4>Our team</h4>
                <p>We are a team of three students of Applied Computer Science at DHBW Karlsruhe: Lukas, Anett and
                    Ben.</p>
                <p>In our course Software Engineering, we invented the idea of developing a web calendar application
                    that will simplify everyone&#8217;s life and therefore created EventLAB.</p>
                <p>If you have any questions, please feel free to send us an <a href="mailto:eventlab@jupiterspace.de">e-mail</a> or comment on our
                    <a href="https://github.com/tarjmp/eventlab" target="_blank">GitHub repository</a> at any time.</p>
                <br>
                <h4>Where to find us</h4>
                <p>DHBW Karlsruhe <br>
                    Erzbergerstraße 121 <br>
                    76133 Karlsruhe <br>
                    Germany</p>
            </div>
        </div>
    </div>
@endsection
