@extends('layouts.app')
@section('content')

<div class="card">
    <div class="card-body">
        <div class="form-group">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ __('id') }}
                        </th>
                        <td>
                            <span class="badge badge-default">
                                {{ $auditLog->id }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('Description') }}
                        </th>
                        <td>
                            <span class="badge badge-default">
                                {{ $auditLog->description }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('Subject ID') }}
                        </th>
                        <td>
                            <span class="badge badge-default">
                                {{ $auditLog->subject_id }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('Subject Type') }}
                        </th>
                        <td>
                            <span class="badge badge-default">
                                {{ $auditLog->subject_type }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('User') }}
                        </th>
                        <td>
                            <span class="badge badge-default">
                                {{ $auditLog->user->email }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('Propertires') }}
                        </th>
                        <td>
                            <div>
                            @foreach($auditLog->properties as $key => $property)
                                    <div>
                                        <span class="badge badge-success">{{$key}}</span>
                                        <span class="badge badge-default"><?php echo $property ?></span>
                                    </div> 
                                @endforeach 
                            </div>
                            
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('Host') }}
                        </th>
                        <td>
                            <span class="badge badge-default">
                                {{ $auditLog->host }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('Date') }}
                        </th>
                        <td>
                            <span class="badge badge-default">
                                {{ $auditLog->created_at }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
