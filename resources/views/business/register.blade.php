@extends('layouts.auth2')
@section('title', __('lang_v1.register'))

@section('content')

    <div style="color: white;
    text-align: center;
    font-size: 20px;">
        للتسجيل ضمن الباقات الرجاء الاتصال بالدعم الفني عبر الوتس اب
    </div>
    <br>
    <div style="    text-align: center;
    font-size: 20px;
    text-decoration: underline;
    color: aquamarine;
}">
        <a href="https://wa.me/967777335118">الدعم الفني</a>
    </div>
@stop
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.change_lang').click(function() {
                window.location = "{{ route('business.getRegister') }}?lang=" + $(this).attr('value');
            });
        })
    </script>
@endsection
