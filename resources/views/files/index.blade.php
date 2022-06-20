@extends('layouts.app')

@section('title', 'فایلها')

@section('content')

<div class="row justify-content-center">
  <div class="card col-md-10 mt-5">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">نام فایل</th>
                    <th scope="col">نوع فایل</th>
                    <th scope="col">حجم فایل</th>
                    <th scope="col">زمان فایل</th>
                    <th scope="col">سطح دسترسی</th>
                    <th scope="col">عملیات</th>
                </tr>
            </thead>
            <tbody>

                @foreach($files as $file)
                <tr>
                    <td>{{$file->name}}</td>
                    <td>{{$file->type}}</td>
                    <td>{{number_format($file->size / (1024 * 1024), 2)}} مگابایت</td>
                    @if(is_null($file->time))
                    <td>ندارد</td>
                    @else
                    <td>{{$file->time}} ثانیه</td>
                    @endif
                    @if($file->is_private)
                    <td>خصوصی</td>
                    @else
                    <td>عمومی</td>
                    @endif
                    <td>
                        <a href="{{ route('files.show',$file) }}" class='btn btn-primary btn-sm'>دانلود</a>
                        <form action="{{ route('files.destroy', $file) }}" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class='btn btn-primary btn-sm'>حذف</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>



@endsection
