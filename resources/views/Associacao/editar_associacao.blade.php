@extends('layouts.app2')
@section('content')


<div class="row extra-space">
    <div class="col-md-12 upper-div">
        <div class="especifies">
            <br>
            <form class="formulario" method="post" action="{{ route('associacao.editarAssociacao.salvar', ['associacao' => $associacao->id]) }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-10">
                    <h1 class="marker">Editar associação</h1>
                </div>
            </div>
            <hr class="outliner"></hr>
            <br>
            <div class="form-row">
              <div class="col-md-12 mb-3">
                <label class="mark">Dados</label>
              </div>
              <div class="col-md-12 mb-3">
                <hr class="divider"></hr>
              </div>
            </div>
            <div class="form-row">
              <div class="col-md-9">
                <div class="form-row">
                  <div class="col-md-12 mb-3">
                    <label class="label-static required">Nome</label>
                    <input type="text" class="form-control input-stl @error('nome') is-invalid @enderror" id="nome_entidade" name="nome" placeholder="Nome Completo" value="{{ old('nome', $associacao->user->nome) }}">
                    @error('nome')
                      <div id="validationServer03Feedback" class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>
                <div class="form-row">
                    <div class="col-md-12 mb-3">
                        <label class="label-static required" for="Email">CNPJ</label>
                        <input type="number" class="form-control input-stl @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $associacao->user->email) }}">
                        @error('email')
                          <div id="validationServer03Feedback" class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                  <div class="col-md-12 mb-3">
                      <label class="label-static">Email</label>
                      <input type="text" class="form-control input-stl @error('email2') is-invalid @enderror" id="email2" name="email2" placeholder="Email" value="{{ old('email2', $associacao->user->email2) }}">
                      @error('email2')
                        <div id="validationServer03Feedback" class="invalid-feedback">
                          {{ $message }}
                        </div>
                      @enderror
                  </div>
                </div>
              </div>
              <div class="col-md-3 mb-3">
                <img id="logo-associacao" src="{{$associacao->logo != null ? asset('storage/'.$associacao->logo) : asset('images/logo_associcao_default.png')}}" alt="Logo da associação">
                <small>Tamanho ideal: 222 X 250</small>
                <div style="display: none;">
                  <input type="file" name="logo_associacao" id="logo_associacao" accept=".png">
                </div>
                @error('logo_associacao')
                  <div id="validationServer03Feedback" class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                  <label class="label-static required">Telefone</label>
                  <input type="text" class="form-control input-stl @error('telefone') is-invalid @enderror" id="telefone" name="telefone" placeholder="Telefone da OCS" value="{{ old('telefone', $associacao->user->telefone) }}">
                  @error('telefone')
                    <div id="validationServer03Feedback" class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
              </div>
              <div class="col-md-6 mb-4">
                  <label class="label-static">Celular</label>
                  <input  class="form-control input-stl @error('celular') is-invalid @enderror" type="number" class="form-control" name="celular" placeholder="Celular" value="{{ old('telefone', $associacao->celular) }}">
                  @error('celular')
                    <div id="validationServer03Feedback" class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
              </div>
            </div>
            <br>
            <div class="form-row">
              <div class="col-md-12 mb-3">
                <label class="mark">Endereço</label>
              </div>
              <div class="col-md-12 mb-3">
                <hr class="divider"></hr>
              </div>
            </div>
            <div class="form-row">
              <div class="col-md-8">
                  <label class="label-static required">Cidade</label>
                  <input type="text" class="form-control input-stl @error('cidade') is-invalid @enderror" id="cidade" name="cidade" value="{{ old('cidade',$associacao->user->endereco->cidade) }}">
                  @error('cidade')
                    <div id="validationServer03Feedback" class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
              </div>
              <div class="col-md-4">
                  <label class="label-static required">Estado</label>
                  <input type="text" class="form-control input-stl @error('estado') is-invalid @enderror" id="estado" name="estado" value="{{ old('estado', $associacao->user->endereco->estado) }}">
                  @error('estado')
                    <div id="validationServer03Feedback" class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                  <br>
              </div>
            </div>

            <div class="form-row">
              <div class="col-md-4">
                  <label class="label-static">Bairro</label>
                  <input type="text" class="form-control input-stl @error('bairro') is-invalid @enderror" id="bairro" name="bairro" placeholder="Bairro da associação" value="{{ old('bairro', $associacao->user->endereco->bairro) }}">
                  @error('bairro')
                    <div id="validationServer03Feedback" class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                  <br>
              </div>
                <div class="col-md-6">
                    <label class="label-static">Logradouro</label>
                    <input type="text" class="form-control input-stl @error('nome_rua') is-invalid @enderror" id="nome_rua" name="nome_rua" placeholder="Logradouro da associação" value="{{ old('nome_rua', $associacao->user->endereco->nome_rua) }}">
                    @error('nome_rua')
                      <div id="validationServer03Feedback" class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>
                <div class="col-md-2">
                    <label class="label-static">Nº</label>
                    <input type="text" class="form-control input-stl @error('numero_casa') is-invalid @enderror" id="numero_casa" name="numero_casa" value="{{ old('numero_casa', $associacao->user->endereco->numero_casa) }}">
                    @error('numero_casa')
                      <div id="validationServer03Feedback" class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label class="label-static">CEP</label>
                    <input type="text" class="form-control input-stl @error('cep') is-invalid @enderror" id="cep" name="cep" value="{{ old('cep', $associacao->user->endereco->cep) }}">
                    @error('cep')
                      <div id="validationServer03Feedback" class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                    <br>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label class="label-static">Ponto de Referência</label>
                    <textarea type="text" class="form-control input-stl @error('ponto_referencia') is-invalid @enderror" id="ponto_referencia" name="ponto_referencia">{{ $associacao->user->endereco->ponto_referencia}}</textarea>
                    @error('ponto_referencia')
                      <div id="validationServer03Feedback" class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                    <br>
                </div>
            </div>
            <hr class="outliner"></hr>
            <div class="row">
              <div class="col-md-8 mb-3">
                <label style="color: red" class="label-static required">Campos obrigatórios</label>
              </div>
              <div class="col-md-4 mb-3">
                <button class="btn botao-submit bg-verde" type="submit">Atualizar</button>
              </div>
            </div>
            </form>

    </div>
  </div>
</div>
<script>
  $(document).ready(function() {
    $('#logo-associacao').click(function(){
      $('#logo_associacao').click();
      $('#logo_associacao').change(function(){
        if (this.files && this.files[0]) {
          console.log(this.files[0])
          var file = new FileReader();
          file.onload = function(e) {
            document.getElementById("logo-associacao").src = e.target.result;
          }
          file.readAsDataURL(this.files[0]);
        }
      });
    });
  });
</script>
@endsection
