<?php 
$regraEscola = new Escola();
$regraUsuario = new Usuario();
$escola = $regraEscola->pegarAtual();
$usuarioAtual = $regraUsuario->pegarAtual();

$urlEditar  = WEB_PATH.'/admin/usuario/';
$urlEditar .= strtolower(sanitize_slug($usuarioAtual->nome));
$urlEditar .= '-'.$usuarioAtual->id_usuario;
?>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo WEB_PATH; ?>/admin"><?php echo $escola->nome; ?></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <?php if (!is_null($usuarioAtual)) : ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Turmas <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo WEB_PATH; ?>/admin/cursos"><i class="icon icon-bars"></i> Cursos</a></li>
                        <li><a href="<?php echo WEB_PATH; ?>/admin/cursos?curso=0"><i class="icon icon-plus"></i> Novo Curso</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="<?php echo WEB_PATH; ?>/admin/turmas"><i class="icon icon-bars"></i> Turmas</a></li>
                        <li><a href="<?php echo WEB_PATH; ?>/admin/turmas?turma=0"><i class="icon icon-plus"></i> Nova Turma</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Alunos <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo WEB_PATH; ?>/admin/pessoas?tipo=responsavel"><i class="icon icon-users"></i> Responsáveis</a></li>
                        <li><a href="<?php echo WEB_PATH; ?>/admin/pessoas?tipo=aluno"><i class="icon icon-users"></i> Alunos</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="<?php echo WEB_PATH; ?>/admin/pessoa-form"><i class="icon icon-user"></i> Novo aluno</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Financeiro <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo WEB_PATH; ?>/admin/movimentos"><i class="icon icon-users"></i> Movimentos</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="<?php echo WEB_PATH; ?>/admin/movimentos?ac=gerar&ano=<?php echo date('Y'); ?>"><i class="icon icon-calendar"></i> <?php echo "Gerar movimentos de ".date('Y'); ?></a> </li>
                        <li><a href="<?php echo WEB_PATH; ?>/admin/movimento-imprimir?ano=<?php echo date('Y'); ?>"><i class="icon icon-print"></i> <?php echo "Imprimir Carnês de ".date('Y'); ?></a> </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Usuários <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo WEB_PATH; ?>/admin/usuarios"><i class="icon icon-users"></i> Usuários</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="<?php echo WEB_PATH; ?>/admin/usuario"><i class="icon icon-user"></i> Novo Usuário</a></li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if (!is_null($usuarioAtual)) : ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <img src="<?php echo get_gravatar($usuarioAtual->email, 20); ?>" class="img-circle" />
                        <?php echo $usuarioAtual->nome; ?> <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo $urlEditar; ?>"><i class="icon icon-pencil"></i> Alterar</a></li>
                        <li><a href="<?php echo WEB_PATH; ?>/admin/logout"><i class="icon icon-remove"></i> Sair</a></li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
            <?php if (!is_null($usuarioAtual)) : ?>
            <form method="GET" action="<?php echo WEB_PATH; ?>/admin/pessoas" class="navbar-form navbar-right" role="search">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control" name="p" value="<?php echo $_GET['p']; ?>" placeholder="Buscar...">
                        <div class="input-group-addon"><i class="icon icon-search"></i></div>
                    </div>
                </div>
            </form>
            <?php endif; ?>
        </div><!--/.nav-collapse -->
    </div>
</nav>