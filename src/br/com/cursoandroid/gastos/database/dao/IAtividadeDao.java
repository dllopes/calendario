package br.com.cursoandroid.gastos.database.dao;

import java.util.ArrayList;

import android.content.Context;
import br.com.cursoandroid.gastos.dominio.Atividade;

public interface IAtividadeDao {
	public Atividade insert(Atividade obj);
	public Atividade update(Atividade obj);
	public Atividade delete(Atividade obj);
	public Atividade get(Integer id);
	public ArrayList<Atividade> getAll();
	public ArrayList<Atividade> getAllDespesas();
	public ArrayList<Atividade> getAllReceitas();
	public void setContext(Context context);
}
