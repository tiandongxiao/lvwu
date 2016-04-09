@extends('base.master')
@section('content')
    <div class="text-center">
        <h3>{{$price/100}} 元</h3>
        {!! QrCode::encoding('UTF-8')->size(400)->generate($url) !!}
        <p>请使用微信扫码支付</p>
    </div>
    @endsection