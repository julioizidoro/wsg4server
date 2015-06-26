<h1>Projeto SenaiRunner
</h1>
<h2>Origem</h2>
<ul>
<li>Curso de Pós-Graduação Lato Sensu em Sistemas Web e
Dispositivos Móveis (SENAI/SC)</li>
<li>Disciplina: Tecnologia Web Services</li>
<li>Professor: Rangel Torrezan</li>
</ul>
<h2>Equipe de Desenvolvimento</h2>
<ul>
<li>André Luiz Banki (&#8203;<a href="mailto:andre.banki@gmail.com">andre.banki@gmail.com</a>)</li>
<li>Alex Pierry (&#8203;<a href="mailto:alex@teclan.com.br">alex@teclan.com.br</a>)</li>
<li>Carlos Ceolato (<a href="mailto:carlos@ceolato.com.br">carlos@ceolato.com.br</a>)</li>
<li>Julio Izidoro (<a href="mailto:jizidoro@globo.com">jizidoro@globo.com</a>)</li>
</ul>
<h2>Requisitos do sistema</h2>
<ul>
<li>Plataforma para gestão, inscrição e acompanhamento de
corridas de rua da Associação Amigos do Coração.</li>
<li>O organizador poderá cadastrar, incluir, atualizar e
excluir corridas através de uma plataforma WEB.
<ul>
<li>Informações minimas da corrida: nome, data, cidade,
estado, descrição, valor de inscrição, status (Agendada, Cancelada)</li>
</ul>
</li>
<li>O organizador poderá inativar
um corredor ou atualizar
suas informações.</li>
<li>O corredor poderá realizar seu próprio cadastro</li>
<ul>
<li>Informações minimas do corredor: Nome, Data Nascimento,
Cidade e Estado.</li>
</ul>
<li>O corredor poderá realizar inscrições para qualquer corrida
aberta.</li>
<ul>
<li>Informações minimas para inscrição: Corredor, Corrida,
Status do Pagamento</li>
</ul>
</ul>
<h2>Repositório</h2>
<ul>
<li>
<a href="https://github.com/julioizidoro/wsg4server.git">https://github.com/julioizidoro/wsg4server</a>
</li>
</ul>
<h2>Modelo de dados</h2>
<p style="margin-bottom: 0.28cm; line-height: 108%;" align="center"><img src="http://ceolato.com.br/wsg4server/docs/ModeloBD.PNG" name="Figura1" align="bottom" border="0" height="248" width="592">
</p>
<h2>Definição da API</h2>
<table style="page-break-before: auto; page-break-after: auto; page-break-inside: auto; width: 100%;" border="1" cellpadding="0" cellspacing="0">
<col width="20%"> <col width="20%"> <col width="20%"> <col width="20%"> <col width="20%"> <tbody>
<tr valign="top">
<td class="header">URI</td>
<td class="firstline">GET</td>
<td class="firstline">POST</td>
<td class="firstline">PUT</td>
<td class="firstline">DELETE</td>
</tr>
<tr valign="top">
<td class="firstcolumn">
\corridas
</td>
<td class="cell">
Retorna a lista de Corridas
</td>
<td class="cell">
Cria uma Corrida (*2)
</td>
<td class="cell">
Não usado
</td>
<td class="cell">
Não usado (apagaria todas as Corridas)
</td>
</tr>
<tr valign="top"><td class="firstcolumn">
\corridas\ultimas
</td><td class="cell">
Retorna a lista de Corridas com data anterior à atual
</td><td class="cell">
Não usado
</td><td class="cell">Não usado
</td><td class="cell">
Não usado
</td></tr><tr valign="top"><td class="firstcolumn">
\corridas\proximas
</td><td class="cell">
Retorna a lista de Corridas com data igual ou superior à atual
</td><td class="cell">
Não usado
</td><td class="cell">Não usado
</td><td class="cell">
Não usado
</td></tr><tr valign="top"><td class="firstcolumn">
\corridas\abertas
</td><td class="cell">
Retorna a lista de Corridas com status igual a "Aberta"
</td><td class="cell">
Não usado
</td><td class="cell">Não usado
</td><td class="cell">
Não usado
</td></tr><tr valign="top">
<td class="firstcolumn">
\corridas\[id]
</td>
<td class="cell">
Retorna os dados dessa Corrida (*1)
</td>
<td class="cell">
Não usado
</td>
<td class="cell">
Atualiza os dados dessa Corrida (*1)(*2)
</td>
<td class="cell">
Apaga essa Corrida (*1)
</td>
</tr>
<tr valign="top">
<td class="firstcolumn">
\corredores
</td>
<td class="cell">
Volta a lista de Corredores
</td>
<td class="cell">
Cria um Corredor (*2)
</td>
<td class="cell">
Não usado
</td>
<td class="cell">
Não usado (apagaria todos os Corredores)
</td>
</tr>
<tr valign="top"><td class="firstcolumn">
\corredores\ativos
</td><td class="cell">
Retorna a lista de Corredores com status igual a "Ativo"
</td><td class="cell">
Não usado
</td><td class="cell">Não usado
</td><td class="cell">
Não usado
</td></tr><tr valign="top">
<td class="firstcolumn">
\corredores\[id]
</td>
<td class="cell">
Volta os dados desse Corredor (*1)
</td>
<td class="cell">
Não usado
</td>
<td class="cell">
Atualiza os dados desse Corredor (*1) (*2)
</td>
<td class="cell">
Apaga esse Corredor (*1)
</td>
</tr>
<tr valign="top">
<td class="firstcolumn">
\corridas\[id]\corredores
</td>
<td class="cell">
Lista de Corredores inscritos nessa Corrida (*1)
</td>
<td class="cell">
Não usado
</td>
<td class="cell">
Não usado
</td>
<td class="cell">
Não usado (removeria todas as Inscrições de
Corredores nessa Corrida)
</td>
</tr>
<tr valign="top">
<td class="firstcolumn">
\corredores\[id]\corridas
</td>
<td class="cell">
Volta a lista de Corridas nas quais esse
Corredor está inscrito (*1)
</td>
<td class="cell">
Não usado
</td>
<td class="cell">
Não usado
</td>
<td class="cell">Não usado (removeria todas as
Inscrições desse
Corredor em qualquer Corrida)</td>
</tr>
<tr valign="top">
<td class="firstcolumn">
\corredores\[id]\corridas\[id]<br>
<br>
\corridas\[id]\corredores\[id]
</td>
<td class="cell">Volta os dados da Inscrição desse
Corredor
nessa Corrida (*1)(*4)</td>
<td class="cell">Cria uma Inscrição para um Corredor
nessa
Corrida (*1)(*3)(*5)(*6)</td>
<td class="cell">Atualiza os dados dessa Inscrição (*1)(*2)</td>
<td class="cell">Remove essa Inscrição (*1)(*4)</td>
</tr>
</tbody>
</table>
<p>(*1)
Caso não seja encontrada a entidade com o ID informado, será
retornado Erro 404.</p><p>(*2)
Caso ocorra algum erro na execução da instrução SQL, será retornado
Erro 412. Sugere-se conferir a estrutura dos dados informados na
requisição com a estrutura do banco descrita neste documento.</p>
<p>(*3)
A inscrição é efetuada sem receber nenhum
parâmetro. É registrada sempre com o status de pagamento
“false” (supondo que a inscrição é
criada sempre antes da confirmação de
pagamento) e os
demais dados zerados (dados referentes à participação
do corredor nessa corrida).</p><p>(*4) Caso não exista inscrição para o Corredor nessa Corrida, também será retornado Erro 404.</p><p>(*5) Caso o status dessa Corrida seja diferente de "Aberta", será retornado Erro 403.</p><p>(*6) Caso o status desse Corredor seja diferente de "Ativo", também será retornado Erro 403.</p>
</body></html>
