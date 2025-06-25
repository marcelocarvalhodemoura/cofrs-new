<p>Olá {{$details['name']}}!</p>
<p>Seu acesso ao sistema foi criado com sucesso:</p>
<p>
  <strong>Usuário:</strong> {{$details['usuario']}}<br />
  <strong>Senha:</strong> {{$details['senha']}}<br />
  <strong>Acesso:</strong> <a href="{{$details['acesso']}}">{{$details['acesso']}}</a>
</p>
