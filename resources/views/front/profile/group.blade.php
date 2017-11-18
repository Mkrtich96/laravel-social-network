<div class="col">
    <div class="card-group">
        <div class="row w-100">
            <div class="col-3 pr-0">
                <div class="card mb-3 mt-3 conversation">
                    <div class="card-header bg-transparent text-dark">Members</div>
                    <div class="card-body text-success">
                        @foreach($conversation->users as $user)
                            <p class="card-text"><img class="rounded-circle conversations-avatar" src="{{ generate_avatar($user) }}">
                                <a href="{{ route('user.guest', $user->id) }}" target="_blank">{{ $user->name }}</a></p>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-9 pl-0">
                <div class='w-100 mt-3 conversation group-id card' data-id="{{ $conversation->id }}">
                    <div class="card-header h-10 message-header">
                        <span class="user-name">{{ $conversation->name }}</span>
                    </div>
                    <div class='card-body message-body'>
                        <div class="card-text">
                            <ul class="convers-message-list">
                                @foreach($conversation->messages as $message)
                                    @if($message->user->id === $auth->id)
                                        <li class='list-group-item list-group-item-primary text-right message-text'>
                                            <img src="{{ $avatar }}" class="rounded-circle conversations-avatar float-right">
                                            {{ $message->message }}
                                            <br>
                                            <cite class='cite' title='{{ $message->created_at }}'>{{ $message->created_at }}</cite>
                                        </li>
                                    @else
                                        <li class='list-group-item list-group-item-success text-left message-text'>
                                            <img src="{{ generate_avatar($message->user) }}" class="rounded-circle conversations-avatar float-left">
                                            <h6 class="mb-1">{{ $message->user->name }}</h6>
                                            {{ $message->message }}
                                            <br>
                                            <cite class='cite' title='{{ $message->created_at }}'>{{ $message->created_at }}</cite>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="input-group message-footer">
                        <input type="text" class="form-control conversation-message" placeholder="Message..."
                               aria-label="Send..." >
                        <span class="input-group-btn">
                            <button class="btn btn-success send" type="submit">Send</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="{{ asset('js/conversation.js') }}"></script>