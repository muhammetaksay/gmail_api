@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @if (isset($customers))
                    <div class="card-header">{{ __('Customers') }}</div>
                @else
                    <div class="card-header">{{ $customer->email }}</div>
                @endif
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (isset($customers))
                    <table class="table table-striped">
                        <thead>
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">Email</th>
                              <th scope="col">Properties</th>
                            </tr>
                          </thead>
                          <tbody>
                              @foreach ($customers as $item)
                              <tr>
                                  <td scope="row">{{ $item->id }}</td>
                                  <td>{{ $item->email }}</td>
                                  <td><a href="{{ route('customer.show', $item->id) }}" class="btn btn-danger">Show</a></td>
                                   
                              </tr>
                              @endforeach
                          </tbody>
                    </table>
                    
                    @else
                        <a href="{{ route('customers') }}" class="btn btn-primary float-right w-auto"> Back </a>
                        {{-- <h2 class="float-left">  {{ $customer->email }}  </h2> --}}
                        <hr>

                        @foreach ($mails as $mail)
                        <div style="width: 100%;
                        font-family: helvetica,'helvetica neue',arial,verdana,sans-serif;
                        padding: 0;
                        Margin: 5px 0; overflow:hidden; border: 1px solid red;">
                                <?php 
                                $sanitizedData = strtr($mail->mail_content,'-_', '+/');
                                $decodedMessage = base64_decode($sanitizedData);
                                echo $decodedMessage;
                                ?>                    
                        </div>
                        @endforeach


                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
