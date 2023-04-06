@extends('layouts.app')
@section('breadcrumbs')
    {{
       Breadcrumbs::for('toppage', function ($trail) {
            $trail->push('トップページ', route('toppageoffice'));
       }),
       Breadcrumbs::for('promotioncircle', function ($trail, $year) {
            $trail->parent('toppage');
            $trail->push('サークル推進計画（'.session('circle.circle_name').'）（'.$year.'年）', URL::to('promotion-circle?year='.$year));
       }),
       Breadcrumbs::for('create', function ($trail, $year, $promotion_circle_id, $member_name) {
            $trail->parent('promotioncircle', $year, $promotion_circle_id);
            $trail->push('メンバーレベル登録（'.$member_name.'）');
       })
    }}

    {{ Breadcrumbs::render('create', $year, $promotion_circle_id, $member_name) }}
@endsection
@section('content')
    <div class="float-right btn-area">
        <button class="btn btn-add" id="btn-register">登録</button>
        <a class="btn btn-back" href="{{ URL::to('circlelevel?promotion_circle_id='.$promotion_circle_id) }}">戻る</a>
    </div>

    <div style="margin: 15px">
        <span>Ｙ軸（明るく働きがいのある職場に関するレべル把握項目）</span>
        <table class="table table-circle" id="Y">
            <thead>
            <tr>
                <th></th>
                <th>イ</th>
                <th>ロ</th>
                <th>ハ</th>
                <th>ニ</th>
                <th>ホ</th>
            </tr>
            </thead>
            <tbody>
            <tr class="text-wall">
                <td>5</td>
                <td>
                    ●全員元気良く業務や活動もテキパキとやり活気がある。<br>
                    ●信頼関係も強く本音で議論しながら進めている。
                </td>
                <td>
                    ●計画的会合し事前準備もしてあり効率が良い。<br>
                    ●積極的に本音で議論しており活気がある。<br>
                    （会合回数、月３回以上）
                </td>
                <td>
                    ●上司・スタッフ・関連部署と常に連携し、難問に積極的に取組、活発に活動している。
                </td>
                <td>
                    ●全体にレベルアップしようと言う雰囲気があり、定期的に勉強会・研修会を行っている
                </td>
                <td>
                    ●挨拶はいきいきと元気良く、職場の雰囲気が明るい。<br>
                    ●４Ｓは全員自主的に行い、良好なレベルを維持している。<br>
                    （挨拶は全員が実施）
                </td>
            </tr>
            <tr class="text-wall">
                <td>4</td>
                <td>
                    ●雰囲気は明るく仲間意識や協調性もある<br>
                    ●業務や活動も協力して進めている
                </td>
                <td>
                    ●予定通りに実施し、全員積極的に発言する。<br>
                    ●本音の議論になると多少遠慮が出る。<br>
                    （会合回数、月３回以上）
                </td>
                <td>
                    ●活動内容によっては、上司・スタッフ・関連部署と連携し活動している
                </td>
                <td>
                    ●レベルアップしたいと言う雰囲気があり、たまに勉強会などを行うが単発に終わっている
                </td>
                <td>
                    ●挨拶は元気よくできる<br>
                    ●４Ｓ全員協力し、まずまずのレベルを維持（挨拶は９０％以上が実施）
                </td>
            </tr>
            <tr class="text-wall">
                <td>3</td>
                <td>
                    ●みんな仲良く協調性もありにぎやかだ<br>
                    ●業務や活動はリーダー主導でやっている
                </td>
                <td>
                    ●予定通りに実施しているがリーダー主導になっている<br>
                    ●本音も出るが黙って従う人もいる<br>
                    （会合回数、月２回以上）
                </td>
                <td>
                    ●活動は自分たちだけでやっており、重要ステップ（テーマ選定、対策立案など）になると上司に相談する
                </td>
                <td>
                    ●レベルアップしたいと言う雰囲気があり、一部自己啓発に励んでいる<br>
                    ●まだ全体の行動になっていない
                </td>
                <td>
                    ●挨拶はきちんとしている<br>
                    ●４Ｓはまずまずのレベル維持だが、一部の人だけが実施<br>
                    ●ルール違反はない<br>
                    （挨拶は８０％以上が実施）
                </td>
            </tr>
            <tr class="text-wall">
                <td>2</td>
                <td>
                    ●業務連絡や会話などハキハキせず活気が感じられない<br>
                    ●協調性がいまいちで業務や活動も遅れがち
                </td>
                <td>
                    ●予定を時々変更しているし、開いても事前準備をしていないことが多く、雑談で終わることが多い<br>
                    （会合回数、月１回以上）
                </td>
                <td>
                    ●困った時は相談するが、サークル単独活動の時が多く、身近な問題解決の域を脱しきれない
                </td>
                <td>
                    ●一部にレベルアップしたいと言う雰囲気はあるが、行動には移せない
                </td>
                <td>
                    ●挨拶するが活気が無い<br>
                    ●職場は時々きれいにするが維持できない<br>
                    ●ルール違反もときどき発生<br>
                    （挨拶は５０％以上が実施）
                </td>
            </tr>
            <tr class="text-wall">
                <td>1</td>
                <td>
                    ●全般的に暗い雰囲気があり仲間意識や協調性も無く業務や活動も目標未達が多い
                </td>
                <td>
                    ●会合は開くが意見は無い<br>
                    ●リーダーもまとめが出来ず、開かない事もある<br>
                    （会合回数、月１回以下）
                </td>
                <td>
                    ●上司が声を掛ければ報告や相談に来るが、サークルからの働き掛けは無い
                </td>
                <td>
                    ●全体が、仕事をやっていればいいと言う雰囲気であり、レベルアップしようと言う意識は無い
                </td>
                <td>
                    ●挨拶ができない<br>
                    ●４Ｓは定常的に不十分<br>
                    ●ルール違反も多い<br>
                    （挨拶実施率は５０％以下）
                </td>
            </tr>
            <tr>
                <td>昨年</td>
                <td>{{ isset($circle_level_lastyear)? $circle_level_lastyear->axis_y_i : null }}</td>
                <td>{{ isset($circle_level_lastyear)? $circle_level_lastyear->axis_y_ro : null }}</td>
                <td>{{ isset($circle_level_lastyear)? $circle_level_lastyear->axis_y_ha : null }}</td>
                <td>{{ isset($circle_level_lastyear)? $circle_level_lastyear->axis_y_ni : null }}</td>
                <td>{{ isset($circle_level_lastyear)? $circle_level_lastyear->axis_y_ho : null }}</td>
            </tr>
            <?php
                $total_x_i = 0;
                $total_x_ro = 0;
                $total_x_ha = 0;
                $total_x_ni = 0;
                $total_x_ho = 0;
                $total_y_i = 0;
                $total_y_ro = 0;
                $total_y_ha = 0;
                $total_y_ni = 0;
                $total_y_ho = 0;
                $count = 0;
                if(isset($circle_level_own)){
                    $total_x_i = $circle_level_own->axis_x_i;
                    $total_x_ro = $circle_level_own->axis_x_ro;
                    $total_x_ha = $circle_level_own->axis_x_ha;
                    $total_x_ni = $circle_level_own->axis_x_ni;
                    $total_x_ho = $circle_level_own->axis_x_ho;
                    $total_y_i = $circle_level_own->axis_y_i;
                    $total_y_ro = $circle_level_own->axis_y_ro;
                    $total_y_ha = $circle_level_own->axis_y_ha;
                    $total_y_ni = $circle_level_own->axis_y_ni;
                    $total_y_ho = $circle_level_own->axis_y_ho;
                    $count += 1;
                }
            ?>
            <tr>
                <td>あなた</td>
                <td><span id="axis_y_i">{{ isset($circle_level_own)? $circle_level_own->axis_y_i : null}}</span></td>
                <td><span id="axis_y_ro">{{ isset($circle_level_own)? $circle_level_own->axis_y_ro : null}}</span></td>
                <td><span id="axis_y_ha">{{ isset($circle_level_own)? $circle_level_own->axis_y_ha : null}}</span></td>
                <td><span id="axis_y_ni">{{ isset($circle_level_own)? $circle_level_own->axis_y_ni : null}}</span></td>
                <td><span id="axis_y_ho">{{ isset($circle_level_own)? $circle_level_own->axis_y_ho : null}}</span></td>
            </tr>
            @foreach($member_list as $yitem)
            <?php
                $circle_level = DB::table('circle_levels')->where('promotion_circle_id', $promotion_circle_id)->where('member_id', $yitem->id)->first();
                if($circle_level){
                    $count++;
                    $total_y_i += $circle_level->axis_y_i;
                    $total_y_ro += $circle_level->axis_y_ro;
                    $total_y_ha += $circle_level->axis_y_ha;
                    $total_y_ni += $circle_level->axis_y_ni;
                    $total_y_ho += $circle_level->axis_y_ho;
                }
            ?>
            <tr>
                <td>{{ $yitem->name }}</td>
                <td>{{ isset($circle_level)? $circle_level->axis_y_i : null}}</td>
                <td>{{ isset($circle_level)? $circle_level->axis_y_ro : null}}</td>
                <td>{{ isset($circle_level)? $circle_level->axis_y_ha : null}}</td>
                <td>{{ isset($circle_level)? $circle_level->axis_y_ni : null}}</td>
                <td>{{ isset($circle_level)? $circle_level->axis_y_ho : null}}</td>
            </tr>
            @endforeach
            <tr>
                <td>平均</td>
                <td><span id="total_axis_y_i">{{ number_format(round(($count > 0)? $total_y_i/$count : 0, 2) , 2)}}</span> </td>
                <td><span id="total_axis_y_ro">{{ number_format(round(($count > 0)? $total_y_ro/$count : 0, 2), 2) }}</span> </td>
                <td><span id="total_axis_y_ha">{{ number_format(round(($count > 0)? $total_y_ha/$count : 0, 2), 2) }}</span> </td>
                <td><span id="total_axis_y_ni">{{ number_format(round(($count > 0)? $total_y_ni/$count : 0, 2), 2) }}</span> </td>
                <td><span id="total_axis_y_ho">{{ number_format(round(($count > 0)? $total_y_ho/$count : 0, 2), 2) }}</span> </td>
            </tr>
            <tr>
                <td>総平均</td>
                <td colspan="5">
                    <span id="total_axis_y_ho">
                        {{ number_format(round(($count > 0)? ($total_y_i/$count + $total_y_ro/$count + $total_y_ha/$count + $total_y_ni/$count + $total_y_ho/$count)/5 : 0, 2), 2) }}
                    </span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div style="margin: 15px">
        <span>Ｘ軸（ＱＣサークルの平均的な能力に関するレベル把握項目）</span>
        <table class="table table-circle" id="X">
            <thead>
            <tr>
                <th></th>
                <th>イ</th>
                <th>ロ</th>
                <th>ハ</th>
                <th>ニ</th>
                <th>ホ</th>
            </tr>
            </thead>
            <tbody>
            <tr class="text-wall">
                <td>5</td>
                <td>
                    ●大半が身についており自然に実践できる
                </td>
                <td>
                    ●大半が理解しており自らの役割を自覚し自主的に活動できる
                </td>
                <td>
                    ●大半は全て理解しており、的を得た使い方や重点事項が出来、メリハリのあるまとめや発表が出来る
                </td>
                <td>
                    ●非常に進んでいる<br>
                    （たいへん良い）<br>
                    （８０％以上）
                </td>
                <td>
                    ●常に問題意識を持っており、新たな発想で積極的に改善（or進言）する
                </td>
            </tr>
            <tr class="text-wall">
                <td>4</td>
                <td>
                    ●大半が理解しており各自が日常業務や問題発生時に何とか実践できる
                </td>
                <td>
                    ●大半が理解して降りておりテーマリーダー主導の下皆が協力しながら活動を進めていくことが出来る
                </td>
                <td>
                    ●大半はQC手法、QCストーリーを使いこなせ、要領よくまとめや発表ができる
                </td>
                <td>
                    ●進んでいる<br>
                    （良い）<br>
                    （７０％）
                </td>
                <td>
                    ●自らの問題ととらえ、さらに工夫を凝らした改善を実施する
                </td>
            </tr>
            <tr class="text-wall">
                <td>3</td>
                <td>
                    ●半数は理解しておりサークルリーダー主導で実践できる
                </td>
                <td>
                    ●半数は理解しておりサークルリーダー手動で活動を進めていくことが出来る
                </td>
                <td>
                    ●半数は手法を３～４使いこなせ、QCストーリーに沿ってまとめることが出来、発表も出来る
                </td>
                <td>
                    ●普通<br>
                    （５０％）
                </td>
                <td>
                    ●自ら進んで改善すことはあまり無いが、上司に言われたことはキチンと実施する
                </td>
            </tr>
            <tr class="text-wall">
                <td>2</td>
                <td>
                    ●一部は理解しているが日常業務や問題発生時に実践できない
                </td>
                <td>
                    ●一部は理解しているものの実際のかつ同意なると上司の指導でしかやれないな
                </td>
                <td>
                    ●一部は手法を１～２使いこなせ、まとめ方も知っているが応用ができず画一的にしか出来ない
                </td>
                <td>
                    ●遅れている<br>
                    （良くない）<br>
                    （４０％以下）
                </td>
                <td>
                    ●従来のやり方に固執しがちだが、上司に言われたことについては何とかやる
                </td>
            </tr>
            <tr class="text-wall">
                <td>1</td>
                <td>
                    ●メンバーの大半は用語を知っている程度で理解はしていない
                </td>
                <td>
                    ●メンバーの大半は運営の仕方を知らず、上司の指示がなければやれない
                </td>
                <td>
                    ●メンバーの大半はQC7つ道具やQCストーリーを解決していない為、活動結果を上手く表現できない
                </td>
                <td>
                    ●同職種の他チームと比較して非常に遅れている<br>
                    （２０％以下）
                </td>
                <td>
                    ●自らは改善しようという意識は無く上司の指示に対しても消極的
                </td>
            </tr>
            <tr>
                <td>昨年</td>
                <td>{{ isset($circle_level_lastyear)? $circle_level_lastyear->axis_x_i : null }}</td>
                <td>{{ isset($circle_level_lastyear)? $circle_level_lastyear->axis_x_ro : null }}</td>
                <td>{{ isset($circle_level_lastyear)? $circle_level_lastyear->axis_x_ha : null }}</td>
                <td>{{ isset($circle_level_lastyear)? $circle_level_lastyear->axis_x_ni : null }}</td>
                <td>{{ isset($circle_level_lastyear)? $circle_level_lastyear->axis_x_ho : null }}</td>
            </tr>
            <td>あなた</td>
            <td><span id="axis_x_i">{{ isset($circle_level_own)? $circle_level_own->axis_x_i : null}}</span></td>
            <td><span id="axis_x_ro">{{ isset($circle_level_own)? $circle_level_own->axis_x_ro : null}}</span></td>
            <td><span id="axis_x_ha">{{ isset($circle_level_own)? $circle_level_own->axis_x_ha : null}}</span></td>
            <td><span id="axis_x_ni">{{ isset($circle_level_own)? $circle_level_own->axis_x_ni : null}}</span></td>
            <td><span id="axis_x_ho">{{ isset($circle_level_own)? $circle_level_own->axis_x_ho : null}}</span></td>
            @foreach($member_list as $xitem)
            <?php
                $circle_level = DB::table('circle_levels')->where('promotion_circle_id', $promotion_circle_id)->where('member_id', $xitem->id)->first();
                if($circle_level){
                    $total_x_i += $circle_level->axis_x_i;
                    $total_x_ro += $circle_level->axis_x_ro;
                    $total_x_ha += $circle_level->axis_x_ha;
                    $total_x_ni += $circle_level->axis_x_ni;
                    $total_x_ho += $circle_level->axis_x_ho;
                }
            ?>
            <tr>
                <td>{{ $xitem->name }}</td>
                <td>{{ isset($circle_level)? $circle_level->axis_x_i : null}}</td>
                <td>{{ isset($circle_level)? $circle_level->axis_x_ro : null}}</td>
                <td>{{ isset($circle_level)? $circle_level->axis_x_ha : null}}</td>
                <td>{{ isset($circle_level)? $circle_level->axis_x_ni : null}}</td>
                <td>{{ isset($circle_level)? $circle_level->axis_x_ho : null}}</td>
            </tr>
            @endforeach
            <tr>
                <td>平均</td>
                <td><span id="total_axis_y_i">{{ number_format(round(($count > 0)? $total_x_i/$count : 0, 2), 2) }}</span> </td>
                <td><span id="total_axis_y_ro">{{ number_format(round(($count > 0)? $total_x_ro/$count : 0, 2), 2) }}</span> </td>
                <td><span id="total_axis_y_ha">{{ number_format(round(($count > 0)? $total_x_ha/$count : 0, 2), 2) }}</span> </td>
                <td><span id="total_axis_y_ni">{{ number_format(round(($count > 0)? $total_x_ni/$count : 0, 2), 2) }}</span> </td>
                <td><span id="total_axis_y_ho">{{ number_format(round(($count > 0)? $total_x_ho/$count : 0, 2), 2) }}</span> </td>
            </tr>
            <tr>
                <td>総平均</td>
                <td colspan="5">
                    <span id="total_axis_y_ho">
                        {{ number_format(round(($count > 0)? ($total_x_i/$count + $total_x_ro/$count + $total_x_ha/$count + $total_x_ni/$count + $total_x_ho/$count)/5 : 0, 2), 2) }}
                    </span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <form id="form-register" method="POST" action="{{ action('CircleLevelController@store') }}">
        @csrf
        <input type="hidden" name="id"  value="{{ isset($circle_level_own)? $circle_level_own->id : 0 }}" />
        <input type="hidden" name="promotion_circle_id"  value="{{ $promotion_circle_id }}" />
        <input type="hidden" name="member_id" value="{{ $member->id }}" />
        <input type="hidden" name="axis_x_i" id="input_axis_x_i" value="{{ isset($circle_level_own)? $circle_level_own->axis_x_i : 0 }}" />
        <input type="hidden" name="axis_x_ro" id="input_axis_x_ro" value="{{ isset($circle_level_own)? $circle_level_own->axis_x_ro : 0 }}" />
        <input type="hidden" name="axis_x_ha" id="input_axis_x_ha" value="{{ isset($circle_level_own)? $circle_level_own->axis_x_ha : 0 }}" />
        <input type="hidden" name="axis_x_ni" id="input_axis_x_ni" value="{{ isset($circle_level_own)? $circle_level_own->axis_x_ni : 0 }}" />
        <input type="hidden" name="axis_x_ho" id="input_axis_x_ho" value="{{ isset($circle_level_own)? $circle_level_own->axis_x_ho : 0 }}" />
        <input type="hidden" name="axis_y_i" id="input_axis_y_i" value="{{ isset($circle_level_own)? $circle_level_own->axis_y_i : 0 }}" />
        <input type="hidden" name="axis_y_ro" id="input_axis_y_ro" value="{{ isset($circle_level_own)? $circle_level_own->axis_y_ro : 0 }}" />
        <input type="hidden" name="axis_y_ha" id="input_axis_y_ha" value="{{ isset($circle_level_own)? $circle_level_own->axis_y_ha : 0 }}" />
        <input type="hidden" name="axis_y_ni" id="input_axis_y_ni" value="{{ isset($circle_level_own)? $circle_level_own->axis_y_ni : 0 }}" />
        <input type="hidden" name="axis_y_ho" id="input_axis_y_ho" value="{{ isset($circle_level_own)? $circle_level_own->axis_y_ho : 0 }}" />
    </form>
@endsection

@section('scripts')
    <script>
        var yi = 0, yro = 0, yha = 0, yni = 0, yho = 0;
        var xi = 0, xro = 0, xha = 0, xni = 0, xho = 0;
        var row, col;

        function clear(T, t) {
            $('#' + T + ' tbody .text-wall td:nth-child(' + t + ')').each(function () {
                $(this).removeClass('table-circle-cell-select');
            });
        }
        @if(isset($circle_level_own))
        $('#X tbody .text-wall:nth-child({{ -($circle_level_own->axis_x_i) + 6 }}) td:nth-child(2)').addClass('table-circle-cell-select');
        $('#X tbody .text-wall:nth-child({{ -($circle_level_own->axis_x_ro) + 6 }}) td:nth-child(3)').addClass('table-circle-cell-select');
        $('#X tbody .text-wall:nth-child({{ -($circle_level_own->axis_x_ha) + 6 }}) td:nth-child(4)').addClass('table-circle-cell-select');
        $('#X tbody .text-wall:nth-child({{ -($circle_level_own->axis_x_ni) + 6 }}) td:nth-child(5)').addClass('table-circle-cell-select');
        $('#X tbody .text-wall:nth-child({{ -($circle_level_own->axis_x_ho) + 6 }}) td:nth-child(6)').addClass('table-circle-cell-select');
        $('#Y tbody .text-wall:nth-child({{ -($circle_level_own->axis_y_i) + 6 }}) td:nth-child(2)').addClass('table-circle-cell-select');
        $('#Y tbody .text-wall:nth-child({{ -($circle_level_own->axis_y_ro) + 6 }}) td:nth-child(3)').addClass('table-circle-cell-select');
        $('#Y tbody .text-wall:nth-child({{ -($circle_level_own->axis_y_ha) + 6 }}) td:nth-child(4)').addClass('table-circle-cell-select');
        $('#Y tbody .text-wall:nth-child({{ -($circle_level_own->axis_y_ni) + 6 }}) td:nth-child(5)').addClass('table-circle-cell-select');
        $('#Y tbody .text-wall:nth-child({{ -($circle_level_own->axis_y_ho) + 6 }}) td:nth-child(6)').addClass('table-circle-cell-select');
        xi = {{ $circle_level_own->axis_x_i }};
        xro = {{ $circle_level_own->axis_x_i }};
        xha = {{ $circle_level_own->axis_x_i }};
        xni = {{ $circle_level_own->axis_x_i }};
        xho = {{ $circle_level_own->axis_x_i }};
        yi = {{ $circle_level_own->axis_y_i }};
        yro = {{ $circle_level_own->axis_y_i }};
        yha = {{ $circle_level_own->axis_y_i }};
        yni = {{ $circle_level_own->axis_y_i }};
        yho = {{ $circle_level_own->axis_y_i }};
        @endif
        function checky(T, k) {
            $('#' + T + ' tbody .text-wall td:nth-child(' + k + ')').on('click', function () {
                clear(T, k);
                var parr = $(this);
                parr.addClass('table-circle-cell-select');

                col = parr.index();
                row = parr.closest('tr').index();

                var y = -row + 5;
                if (T === 'X') {
                    if (k === 2) {
                        xi = y;
                        document.getElementById("axis_x_i").innerHTML = y;
                        document.getElementById("input_axis_x_i").value = y;
                    } else if (k === 3) {
                        xro = y;
                        document.getElementById("axis_x_ro").innerHTML = y;
                        document.getElementById("input_axis_x_ro").value = y;
                    } else if (k === 4) {
                        xha = y;
                        document.getElementById("axis_x_ha").innerHTML = y;
                        document.getElementById("input_axis_x_ha").value = y;
                    } else if (k === 5) {
                        xni = y;
                        document.getElementById("axis_x_ni").innerHTML = y;
                        document.getElementById("input_axis_x_ni").value = y;
                    } else if (k === 6) {
                        xho = y;
                        document.getElementById("axis_x_ho").innerHTML = y;
                        document.getElementById("input_axis_x_ho").value = y;
                    }
                } else {
                    if (k === 2) {
                        yi = y;
                        document.getElementById("axis_y_i").innerHTML = y;
                        document.getElementById("input_axis_y_i").value = y;
                    } else if (k === 3) {
                        yro = y;
                        document.getElementById("axis_y_ro").innerHTML = y;
                        document.getElementById("input_axis_y_ro").value = y;
                    } else if (k === 4) {
                        yha = y;
                        document.getElementById("axis_y_ha").innerHTML = y;
                        document.getElementById("input_axis_y_ha").value = y;
                    } else if (k === 5) {
                        yni = y;
                        document.getElementById("axis_y_ni").innerHTML = y;
                        document.getElementById("input_axis_y_ni").value = y;
                    } else if (k === 6) {
                        yho = y;
                        document.getElementById("axis_y_ho").innerHTML = y;
                        document.getElementById("input_axis_y_ho").value = y;
                    }
                }
            });
        }
        checky('X', 2);
        checky('X', 3);
        checky('X', 4);
        checky('X', 5);
        checky('X', 6);
        checky('Y', 2);
        checky('Y', 3);
        checky('Y', 4);
        checky('Y', 5);
        checky('Y', 6);

        $('.table-circle tbody tr:nth-child(n+6) td:first-child').css('text-align', 'left');
        $('.table-circle tbody tr:nth-child(n+6) td:not(:first-child)').css('text-align', 'center');

        $("#btn-register").click(function(){
            if(xi == 0 || xro == 0 || xha == 0 || xni == 0 || xho == 0 || yi == 0 || yro == 0 || yha == 0 || yni == 0 || yho == 0){
                alert("{{\App\Enums\StaticConfig::$Set_All_Field}}");
            }
            else{
                $("#form-register").submit()
            }
        });

    </script>
@endsection