@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item-trade.css') }}">
@endsection

@section('content')
<div class="trade-layout">
    <aside class="sidebar">
        <h2>その他の取引</h2>
        <ul>
            @foreach($trade_items as $trade_item)
            <li><a href="/trade/{{$trade_item->id}}">{{$trade_item->name}}</a></li>
            @endforeach
        </ul>
    </aside>

    <section class="chat-area">
        <div class="chat-header">
            <h1>
                <img
                    src="{{ $other_user->user_img
            ? asset('storage/profile_images/' . $other_user->user_img)
            : asset('storage/profile_images/default-user.png') }}"
                    class="other-img">
                「{{$other_user->name}}」さんとの取引画面
            </h1>

            @if($item->buyer_id == $self_user->id && !$item->is_finished)
            <form id="finishForm">
                @csrf
                @method('PUT')
                <button type="button" id="finishButton" class="option-button">取引を完了する</button>
            </form>
            @endif

            @if($item->is_finished && !$has_evaluated)
            <script>
                window.showEvaluationModalOnLoad = true;
            </script>
            @endif

            <div id="evaluationModal" class="modal" style="display:none;">
                <div class="modal-content">
                    <h2>取引が完了しました。</h2>
                    <p>今回の取引相手はどうでしたか？</p>
                    <form id="evaluationForm" action="{{ route('trade.evaluate', $item->id) }}" method="POST">
                        @csrf
                        <div class="star-rating">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star" data-value="{{ $i }}">&#9733;</span>
                                @endfor
                        </div>
                        <input type="hidden" name="rate" id="rate" required>

                        <div class="button-area">
                            <button type="submit" class="submit-btn">送信する</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <div class="item-summary">
            <div class="item-summary__image"> <img src="{{ asset('storage/product_images/'.$item->img) }}" alt="商品画像"> </div>
            <div class="item-summary__info">
                <h2 class="item-summary__name">{{ $item->name }}</h2>
                <p class="item-summary__price">¥{{ number_format($item->price) }}</p>
            </div>
        </div>
        <div class="chat-messages">
            @foreach ($chats as $chat)
            @if ($chat->is_self)
            <div class="message self" data-id="{{ $chat->id }}">
                <div class="user-info">{{$self_user->name}}
                    <img src="{{ $self_user->user_img
            ? asset('storage/profile_images/'.$self_user->user_img)
            : asset('storage/profile_images/default-user.png') }}" class="message-img">
                </div>
                <div class="message-bubble">{{ $chat->message }}</div>
                @if($chat->image)
                <div class="message-image">
                    <img src="{{ asset('storage/comment_images/' . $chat->image) }}" alt="送信画像" style="max-width:200px;">
                </div>
                @endif
                <div class="message-actions">
                    <button class="edit-btn">編集</button>
                    <form action="{{ route('chat.destroy', $chat->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn" onclick="return confirm('このメッセージを削除しますか？')">削除</button>
                    </form>
                </div>
            </div>
            @else
            <div class="message other" data-id="{{ $chat->id }}">
                <div class="user-info">{{$other_user->name}}
                    <img src="{{ $other_user->user_img
            ? asset('storage/profile_images/' . $other_user->user_img)
            : asset('storage/profile_images/default-user.png') }}" class="message-img">
                </div>
                <div class="message-bubble">{{ $chat->message }}</div>
            </div>
            @endif
            @endforeach
        </div>

        @if ($errors->any())
        <div class="todo__alert--danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form class="chat-input" action="{{ route('chat.send', ['item' => $item->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="text" name="message" placeholder="取引メッセージを記入してください" value="{{ old('message', $unsentComment->content ?? '') }}">
            <input type="file" id="imageUpload" name="image" accept="image/*" style="display:none;">
            <button type="button" id="imageButton" class="image-btn">画像を追加</button>
            <button type="submit">送信</button>
        </form>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', e => {
                const msgDiv = e.target.closest('.message');
                const currentText = msgDiv.querySelector('.message-bubble').textContent;
                const newText = prompt('メッセージを編集:', currentText);
                if (!newText) return;

                const chatId = msgDiv.dataset.id;
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/chat/update/${chatId}`;

                form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="message" value="${newText}">
                `;

                document.body.appendChild(form);
                form.submit();
            });
        });

        document.getElementById('imageButton').addEventListener('click', () => {
            document.getElementById('imageUpload').click();
        });

        const finishBtn = document.getElementById('finishButton');
        if (finishBtn) {
            finishBtn.addEventListener('click', async () => {
                if (!confirm('取引を完了しますか？')) return;
                const form = document.getElementById('finishForm');
                console.log("URL:", "{{ route('trade.finish', $item->id) }}");
                console.log("CSRF:", form.querySelector('[name=_token]').value);

                const response = await fetch("{{ route('trade.finish', $item->id) }}", {
                    method: "PUT",
                    headers: {
                        "X-CSRF-TOKEN": form.querySelector('[name=_token]').value,
                        "Accept": "application/json",
                    },
                });
                const result = await response.json();

                if (result.success) {
                    document.getElementById('evaluationModal').style.display = 'flex';
                }
            });
        }

        const stars = document.querySelectorAll('.star');
        const rateInput = document.getElementById('rate');

        if (stars.length) {
            stars.forEach(star => {
                star.addEventListener('mouseover', () => {
                    stars.forEach(s => s.classList.remove('hovered'));
                    for (let i = 0; i < star.dataset.value; i++) {
                        stars[i].classList.add('hovered');
                    }
                });

                star.addEventListener('mouseout', () => {
                    stars.forEach(s => s.classList.remove('hovered'));
                });

                star.addEventListener('click', () => {
                    rateInput.value = star.dataset.value;
                    stars.forEach(s => s.classList.remove('selected'));
                    for (let i = 0; i < star.dataset.value; i++) {
                        stars[i].classList.add('selected');
                    }
                });
            });
        }

        const closeModal = document.getElementById('closeModal');
        if (closeModal) {
            closeModal.addEventListener('click', () => {
                document.getElementById('evaluationModal').style.display = 'none';
            });
        }

        //メッセージ保存機能
        const input = document.querySelector('.chat-input input[name="message"]');
        const itemId = "{{ $item->id }}";

        function saveHoldMessage() {
            const message = input.value.trim();
            if (!message) return;

            const data = new FormData();
            data.append('item_id', itemId);
            data.append('message', message);
            data.append('_token', "{{ csrf_token() }}");

            navigator.sendBeacon("{{ route('chat.hold') }}", data);
        }

        window.addEventListener('beforeunload', saveHoldMessage);
    });


    if (window.showEvaluationModalOnLoad) {
        const modal = document.getElementById('evaluationModal');
        if (modal) {
            modal.style.display = 'flex';
        }
    }
</script>
@endsection