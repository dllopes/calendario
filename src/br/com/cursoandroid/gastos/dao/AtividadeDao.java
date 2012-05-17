package br.com.cursoandroid.gastos.dao;

import java.util.ArrayList;
import java.util.Date;

import br.com.cursoandroid.gastos.dominio.Atividade;

public class AtividadeDao implements IAtividadeDao {
	
	//dummy save implementation
	private ArrayList<Atividade> atividades = new ArrayList<Atividade>();
	//dummy persistence implementation
	public AtividadeDao(){
		Atividade dummy;
		for(int i = 15; i < 25; i++){
			dummy = new Atividade();
			dummy.setId((i + 1));
			dummy.setDataInicio(new Date());
			dummy.setNome("teste" + i);
			dummy.setValor((float) (11.5 * (i +1)));
			dummy.setTipo(Atividade.RECEITA);
			this.atividades.add(dummy);
		}
		
		for(int i = 10; i < 15; i++){
			dummy = new Atividade();
			dummy.setId((i + 1));
			dummy.setDataInicio(new Date());
			dummy.setNome("despesa" + i);
			dummy.setValor((float) (11.5 * (i +1)));
			dummy.setTipo(Atividade.DESPESA);
			this.atividades.add(dummy);
		}
	}

	@Override
	public Atividade insert(Atividade obj) {
		// TODO Auto-generated method stub
		this.atividades.add(obj);
		return obj;
	}

	@Override
	public Atividade update(Atividade obj) {
		// TODO Auto-generated method stub
		for(Atividade atv : this.atividades){
			if(atv.getId() == obj.getId())
				return atv;
		}
		return null;
	}

	@Override
	public Atividade delete(Atividade obj) {
		// TODO Auto-generated method stub
		for(Atividade atv : this.atividades){
			if(atv.getId() == obj.getId()){
				this.atividades.remove(atv);
				return obj;
			}
		}
		return null;
	}

	@Override
	public Atividade get(Integer id) {
		// TODO Auto-generated method stub
		for(Atividade atv : this.atividades){
			if(atv.getId() == id){
				return atv;
			}
		}
		return null;
	}

	@Override
	public ArrayList<Atividade> getAll() {
		return this.atividades;
	}

	@Override
	public ArrayList<Atividade> getAllDespesas() {
		ArrayList<Atividade> result = new ArrayList<Atividade>();
		for(Atividade atv : this.atividades){
			if(atv.getTipo() == Atividade.DESPESA)
				result.add(atv);
		}
		return result;
	}

	@Override
	public ArrayList<Atividade> getAllReceitas() {
		ArrayList<Atividade> result = new ArrayList<Atividade>();
		for(Atividade atv : this.atividades){
			if(atv.getTipo() == Atividade.RECEITA)
				result.add(atv);
		}
		return result;
	}
	
}
