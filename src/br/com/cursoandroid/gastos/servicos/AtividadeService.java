package br.com.cursoandroid.gastos.servicos;

import java.util.ArrayList;

import android.content.Context;
import br.com.cursoandroid.gastos.database.dao.DaoFactory;
import br.com.cursoandroid.gastos.database.dao.IAtividadeDao;
import br.com.cursoandroid.gastos.dominio.Atividade;

public class AtividadeService {
	
	private static IAtividadeDao atividadeDao;
	private static AtividadeService singleton;
	
	static {
		AtividadeService.singleton = new AtividadeService();
	}
	
	public static AtividadeService getInstance(Context context){
		AtividadeService.atividadeDao.setContext(context);
		return AtividadeService.singleton;
	}
	
	private AtividadeService() {
		AtividadeService.atividadeDao = DaoFactory.getAtividadeDao();
	}
	
	public Atividade save(Atividade obj){
		if(obj.getId() != null)
			return AtividadeService.atividadeDao.update(obj);
		
		return AtividadeService.atividadeDao.insert(obj);
	}
	
	public ArrayList<Atividade> getAll(String param){
		if(param.equals(Atividade.RECEITA))
			return AtividadeService.atividadeDao.getAllReceitas();
		
		if(param.equals(Atividade.DESPESA))
			return AtividadeService.atividadeDao.getAllDespesas();
		
		return AtividadeService.atividadeDao.getAll();
	}
	
	public String getTotalReceitas(){
		return "não implementado";
	}
	
	public String getTotalDespesas(){
		return "não implementado";
	}
	
	public String getTotal(){
		return "Não implementado";
	}

}
