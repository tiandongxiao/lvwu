@extends('base.master')

@section('content')
@endsection


@section('script')
    <script src="/js/pingpp-pc.js"></script>
    <script>
        var charge = {!! $charge !!};
        pingppPc.createPayment(charge,function (result,err) {
            //处理错误的信息
        });
    </script>
@endsection
