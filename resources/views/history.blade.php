@extends('layouts.app')

@section('content')
    <section class="section">
        <hr>
        <form method="post" action="{{url('/')}}">
            {{ csrf_field() }} {{ method_field('POST') }}
            <div class="form-group">
                <label for="email">Дата * ( yyyy-mm-dd )</label>
                <input type="text" name="date_at"
                       class="form-control col-sm-3 {{ $errors->has('date_at') ? ' is-invalid' : '' }}"
                       placeholder="Введите дату *" value="{{old('date_at')}}">
                @if(count($errors))
                    @foreach($errors->all() as $error)
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $error }}</strong>
                        </span>
                    @endforeach
                @endif
            </div>
            <button class="btn btn-primary">Получить температуру</button>
        </form>
        @if(isset($historyByDate) && $historyByDate)
            <h4 class="temp">Температура <strong>{{$historyByDate['temp']}}</strong> градусов. </h4>
        @endif
        <hr>

        <h3>История за Последние 30 дней</h3>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Дата</th>
                <th scope="col">Температура</th>
            </tr>
            </thead>
            <tbody>
            @if($lastHistories)
                @foreach($lastHistories as $lastHistory)
                    <tr>
                        <th scope="row">{{$lastHistory['id']}}</th>
                        <td>{{$lastHistory['date_at']}}</td>
                        <td>{{$lastHistory['temp']}}</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>

    </section>
@endsection