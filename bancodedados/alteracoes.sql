-- André 09/06/2022 --
ALTER TABLE `oraculo_ibreptran`.`escolas`
    ADD COLUMN `idusuario` INT(10) NOT NULL DEFAULT 0 AFTER `exibir_campos`;

ALTER TABLE `oraculo_ibreptran`.`escolas`
    CHANGE COLUMN `idusuario` `idusuario` INT(10) NOT NULL DEFAULT '0' AFTER `slug`;

ALTER TABLE `usuarios_adm`
    ADD COLUMN `gestor_cfc` ENUM('S', 'N') NOT NULL DEFAULT 'S' AFTER `gestor_sindicato`;


CREATE TABLE `usuarios_adm_cfcs` (
  `idusuarios_cfcs` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ativo` ENUM('S', 'N') NOT NULL DEFAULT 'S',
  `data_cad` DATETIME NOT NULL,
  `idcfc` INT(10) UNSIGNED NOT NULL,
  `idusuario` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`idusuarios_cfcs`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_bin;


--jessica 15/02/2023
create table avas_conteudos_imagem
(
    idimagem        int   auto_increment not null,
    titulo          varchar(300)  null,
    imagem_servidor varchar(1000) null,
    idconteudo      int unsigned  not null,
    idava           int unsigned  not null,
    constraint avas_conteudos_imagem_pk
        primary key (idimagem),
    constraint avas_conteudos_imagem_idava
        foreign key (idava) references avas_conteudos (idava),
    constraint avas_conteudos_imagem_idconteudo
        foreign key (idconteudo) references avas_conteudos (idconteudo)
);

alter table avas_conteudos_imagem
    change imagem_servidor imagem_exibicao_servidor varchar(1000) null;

alter table avas_conteudos_imagem
    add imagem_exibicao_nome varchar(300) null;

alter table avas_conteudos_imagem
    add imagem_exibicao_tipo varchar(100) null;

alter table avas_conteudos_imagem
    add imagem_exibicao_tamanho int unsigned null;

alter table avas_conteudos_imagem
    add link varchar(300) null;


-- jessica 30/05/2023
create table classificacao_dre
(
    idclassificacao int auto_increment,
    nome            varchar(300) null,

    constraint classificacao_dre_pk
        primary key (idclassificacao)

);

alter table centros_custos
    add idclassificacao int null;

alter table centros_custos
    add constraint centros_custos_classificacao_dre_null_fk
        foreign key (idclassificacao) references classificacao_dre (idclassificacao);