package br.com.cursoandroid.gastos;

import br.com.cursoandroid.gastos.dominio.Atividade;
import br.com.cursoandroid.gastos.servicos.AtividadeService;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

public class CadastroActivity extends Activity {
	private Context self = this;
	private String tipoCadastro;
	private OnClickListener btnCadastrarClickListener = new Button.OnClickListener() {
		@Override
		public void onClick(View view) {
			EditText inputnome = (EditText) findViewById(R.id.inputNome);
			EditText inputvalor = (EditText) findViewById(R.id.inputValor);
			DatePicker inputdata = (DatePicker) findViewById(R.id.datePickerData);
			
			Atividade nova = new Atividade();
			AtividadeService servico = AtividadeService.getInstance(self);
			try {
				nova.setDataInicio(inputdata.getDayOfMonth(), inputdata.getMonth(), inputdata.getYear());
				nova.setNome(inputnome.getText().toString());
				nova.setValor(Float.parseFloat(inputvalor.getText().toString()));
				nova.setTipo(tipoCadastro);
				
				servico.save(nova);
				
				Toast.makeText(self, "Cadastrado com sucesso!", Toast.LENGTH_LONG).show();
				
				Intent intent = new Intent(self, ListagemActivity.class);
				intent.putExtra("tipo", tipoCadastro);
				startActivity(intent);
				
			} catch(Exception e){
				
				Toast.makeText(self, "Erro ao cadastrar", Toast.LENGTH_LONG).show();
			}
		}
	}; 
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.cadastro_atividade);
		
		Bundle extras = getIntent().getExtras();
		tipoCadastro = extras.getString("tipocadastro");
		
		TextView tituloCadastro = (TextView) findViewById(R.id.labelForm);
		
		if(tipoCadastro.equals(Atividade.DESPESA))
			tituloCadastro.setText("Cadastro de Despesa");
		else
			tituloCadastro.setText("Cadastro de Receita");
			
		
		Button btnCadastrar = (Button)findViewById(R.id.btnCadastrar);
		btnCadastrar.setOnClickListener(this.btnCadastrarClickListener);
	}

}
