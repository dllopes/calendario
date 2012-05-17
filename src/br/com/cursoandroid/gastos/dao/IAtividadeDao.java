package br.com.cursoandroid.gastos.dao;

import java.util.ArrayList;

import br.com.cursoandroid.gastos.dominio.Atividade;

public interface IAtividadeDao {
	public Atividade insert(Atividade obj);
	public Atividade update(Atividade obj);
	public Atividade delete(Atividade obj);
	public Atividade get(Integer id);
	public ArrayList<Atividade> getAll();
}
