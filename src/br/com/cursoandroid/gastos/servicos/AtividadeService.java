package br.com.cursoandroid.gastos.servicos;

import java.util.ArrayList;

import br.com.cursoandroid.gastos.dao.DaoFactory;
import br.com.cursoandroid.gastos.dao.IAtividadeDao;
import br.com.cursoandroid.gastos.dominio.Atividade;

public class AtividadeService {
	
	private static IAtividadeDao atividadeDao;
	private static AtividadeService singleton;
	
	static {
		AtividadeService.singleton = new AtividadeService();
	}
	
	public static AtividadeService getInstance(){
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
		ArrayList<Atividade> atividades = this.getAll(Atividade.RECEITA);
		float result = 0;
		
		for(Atividade atv: atividades){
			result += atv.getValor();
		}
		
		return "R$ " + result;
	}
	
	public String getTotalDespesas(){
		ArrayList<Atividade> atividades = this.getAll(Atividade.DESPESA);
		float result = 0;
		
		for(Atividade atv: atividades){
			result += atv.getValor();
		}
		
		return "R$ " + result;
	}
	
	public String getTotal(){
		ArrayList<Atividade> atividadesReceitas = this.getAll(Atividade.RECEITA);
		float receitas = 0;
		
		for(Atividade atv: atividadesReceitas){
			receitas += atv.getValor();
		}
		
		ArrayList<Atividade> atividadesDespesas = this.getAll(Atividade.DESPESA);
		float despesas = 0;
		
		for(Atividade atv: atividadesDespesas){
			despesas += atv.getValor();
		}
		
		
		return "R$ " + (receitas - despesas);
	}

}
